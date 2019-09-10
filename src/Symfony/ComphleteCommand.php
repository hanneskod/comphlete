<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\Symfony;

use hanneskod\comphlete\Completer;
use hanneskod\comphlete\InputFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class ComphleteCommand extends Command
{
    private const BASH_POSTFIX = '_comphlete_load_script.sh';

    /** @var ?DefinitionProcessor */
    private $definitionProcessor;

    public function __construct(DefinitionProcessor $definitionProcessor = null)
    {
        parent::__construct();
        $this->definitionProcessor = $definitionProcessor;
    }

    protected function configure()
    {
        $this
            ->setName('_comphlete')
            ->setHidden(true)
            ->addArgument('line', InputArgument::OPTIONAL, '', '')
            ->addArgument('cursor', InputArgument::OPTIONAL, '', '0')
            ->addOption('generate-bash-script', null, InputOption::VALUE_NONE)
            ->addOption('app-name', null, InputOption::VALUE_REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        if ($input->getOption('generate-bash-script')) {
            /** @var string|null */
            $appName = $input->getOption('app-name');
            $output->write($this->generateBashScript((string)$appName));
            return 0;
        }

        $definition = new SymfonyConsoleDefinition($this->getApplication());

        if ($this->definitionProcessor) {
            $definition = $this->definitionProcessor->processDefinition($definition);
        }

        $completer = new Completer($definition);

        /** @var string */
        $line = $input->getArgument('line');

        /** @var string */
        $cursor = $input->getArgument('cursor');

        $input = (new InputFactory)->createFromValues($line, (int)$cursor);

        $suggestions = $completer->complete($input);

        $output->write(implode(' ', $suggestions));
    }

    private function generateBashScript(string $appName): string
    {
        $appName = $appName ?: $this->getApplication()->getName();

        $fname = sys_get_temp_dir() . '/' . $appName . self::BASH_POSTFIX;

        $script = <<<END_OF_SCRIPT
#/usr/bin/env bash

_{$appName}_comphletions() {
    COMPREPLY=($(compgen -W "$({$appName} _comphlete "\${COMP_LINE}" "\${COMP_POINT}")"))
}

complete -o default -F _{$appName}_comphletions {$appName}

END_OF_SCRIPT;

        file_put_contents($fname, $script);

        return $fname;
    }
}
