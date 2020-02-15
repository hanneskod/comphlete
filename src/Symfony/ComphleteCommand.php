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
    const BASH_POSTFIX = '_comphlete_load_script.sh';

    const IFS = '|';

    /** @var ?DefinitionProcessor */
    private $definitionProcessor;

    public function __construct(DefinitionProcessor $definitionProcessor = null)
    {
        parent::__construct();
        $this->definitionProcessor = $definitionProcessor;
    }

    /**
     * @return void
     */
    protected function configure()
    {
        $this
            ->setName('_complete')
            ->setHidden(true)
            ->addArgument('line', InputArgument::OPTIONAL, '', '')
            ->addArgument('cursor', InputArgument::OPTIONAL, '', '0')
            ->addArgument('to-replace', InputArgument::OPTIONAL, '', '')
            ->addOption('generate-bash-script', null, InputOption::VALUE_NONE)
            ->addOption('app-name', null, InputOption::VALUE_REQUIRED)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
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

        /** @var string */
        $toReplace = $input->getArgument('to-replace');

        $input = (new InputFactory)->createFromValues($line, (int)$cursor, $toReplace);

        $suggestions = $completer->complete($input);

        $output->write(implode(self::IFS, $suggestions));

        return 0;
    }

    private function generateBashScript(string $appName): string
    {
        $appName = $appName ?: $this->getApplication()->getName();
        $subCommand = '_complete --';
        $ifs = self::IFS;

        ob_start();
        require __DIR__ . '/../../bash_load_script_template.php';
        $script = ob_get_clean();

        $fname = sys_get_temp_dir() . '/' . $appName . self::BASH_POSTFIX;

        file_put_contents($fname, $script);

        return $fname;
    }
}
