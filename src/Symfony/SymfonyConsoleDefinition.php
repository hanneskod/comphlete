<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\Symfony;

use hanneskod\comphlete\ContextContainerDefinition;
use hanneskod\comphlete\ContextDefinition;
use hanneskod\comphlete\DefinitionInterface;
use hanneskod\comphlete\LineParser\Tree;
use hanneskod\comphlete\Suggester\SuggesterInterface;
use Symfony\Component\Console\Application;

final class SymfonyConsoleDefinition implements DefinitionInterface
{
    /** @var ContextDefinition[] */
    private $contexts;

    public function __construct(Application $application)
    {
        foreach ($application->all() as $command) {
            if (!$command->isHidden()) {
                $context = new ContextDefinition($command->getName());

                $commandDef = $command->getDefinition();

                $argumentCount = 1;

                foreach ($commandDef->getArguments() as $argumentDef) {
                    $context->addArgument($argumentCount++, (array)$argumentDef->getDefault());
                }

                foreach ($commandDef->getOptions() as $optionDef) {
                    $suggestions = null;

                    if ($optionDef->isValueRequired()) {
                        $suggestions = (array)$optionDef->getDefault();
                    }

                    $context->addOption($optionDef->getName(), $suggestions);
                }

                $this->contexts[$command->getName()] = $context;
            }
        }
    }

    public function getContext(string $context): ContextDefinition
    {
        if (!isset($this->contexts[$context])) {
            throw new \RuntimeException("Unknown context $context");
        }

        return $this->contexts[$context];
    }

    public function getSuggester(Tree $tree): SuggesterInterface
    {
        $contextContainer = new ContextContainerDefinition;

        foreach ($this->contexts as $context) {
            $contextContainer->addContext($context);
        }

        return $contextContainer->getSuggester($tree);
    }
}
