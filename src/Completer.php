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

    /**
     * @return array<string>
     */
    public function complete(Input $input): array
    {
        $suggester = $this->definition->getSuggester($input->getSyntaxTree());

        $node = $input->getCurrentNode();

        if (!$suggester->handles($node)) {
            return [];
        }

        $suggestions = Helper::filter($suggester->getSuggestions($node), $node->getValue());

        if ($node->getValue() == $input->getWordToReplace()) {
            return $suggestions;
        }

        $suggestions = Helper::explode($suggestions);

        return Helper::filter($suggestions, $input->getWordToReplace());
    }
}
