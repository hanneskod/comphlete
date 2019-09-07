<?php

declare(strict_types = 1);

namespace hanneskod\comphlete;

class Completer
{
    /** @var DefinitionInterface */
    private $definition;

    public function __construct(DefinitionInterface $definition)
    {
        $this->definition = $definition;
    }

    public function complete(Input $input): array
    {
        $suggester = $this->definition->getSuggester($input->getSyntaxTree());

        $node = $input->getCurrentNode();

        return $suggester->handles($node) ? $suggester->getSuggestions($node) : [];
    }
}