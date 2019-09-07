<?php

declare(strict_types = 1);

namespace hanneskod\comphlete;

use hanneskod\comphlete\LineParser\Tree;
use hanneskod\comphlete\Suggester\SuggesterInterface;
use hanneskod\comphlete\Suggester\ArgumentSuggester;

final class ContextContainerDefinition implements DefinitionInterface
{
    /** @var ContextDefinition[] */
    private $contexts = [];

    public function addContext(ContextDefinition $context): self
    {
        $this->contexts[$context->getContextName()] = $context;

        return $this;
    }

    public function getSuggester(Tree $tree): SuggesterInterface
    {
        $context = $tree->getFirstArgument()->getValue();

        if (isset($this->contexts[$context])) {
            return $this->contexts[$context]->getSuggester($tree);
        }

        return new ArgumentSuggester([0 => array_keys($this->contexts)]);
    }
}
