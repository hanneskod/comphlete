<?php

declare(strict_types = 1);

namespace hanneskod\comphlete;

use hanneskod\comphlete\LineParser\Tree;
use hanneskod\comphlete\Suggester\SuggesterInterface;
use hanneskod\comphlete\Suggester\CombinedSuggester;
use hanneskod\comphlete\Suggester\ArgumentSuggester;
use hanneskod\comphlete\Suggester\OptionSuggester;
use hanneskod\comphlete\Suggester\OptionValueSuggester;

class ContextDefinition implements DefinitionInterface
{
    /** @var Definition */
    private $definition;

    /** @var string */
    private $name;

    public function __construct(string $name)
    {
        $this->definition = new Definition;
        $this->definition->addArgument(0, [$name]);
        $this->name = $name;
    }

    public function getContextName(): string
    {
        return $this->name;
    }

    public function addArgument(int $argument, $suggestions = []): self
    {
        if (0 === $argument) {
            throw new \LogicException('Unable to redefine first argument in contexts');
        }

        $this->definition->addArgument($argument, $suggestions);
        return $this;
    }

    public function addOption(string $option, $suggestions = null): self
    {
        $this->definition->addOption($option, $suggestions);
        return $this;
    }

    public function getSuggester(Tree $tree): SuggesterInterface
    {
        return $this->definition->getSuggester($tree);
    }
}
