<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\Suggester;

use hanneskod\comphlete\LineParser\Node;
use hanneskod\comphlete\LineParser\OptionValue;
use hanneskod\comphlete\Helper;

final class OptionValueSuggester implements SuggesterInterface
{
    /** @var array<string, array|callable> */
    private $optionMap;

    /**
     * @param array<string, array|callable> $optionMap
     */
    public function __construct(array $optionMap)
    {
        $this->optionMap = $optionMap;
    }

    public function handles(Node $node): bool
    {
        return $node instanceof OptionValue && isset($this->optionMap[$node->getOption()]);
    }

    public function getSuggestions(Node $node): array
    {
        if (!$node instanceof OptionValue) {
            throw new \InvalidArgumentException('Node must be OptionValue');
        }

        $suggestions = $this->optionMap[$node->getOption()] ?? [];

        if (is_callable($suggestions)) {
            $suggestions = $suggestions($node);
        }

        if (!is_array($suggestions)) {
            throw new \UnexpectedValueException('Suggstions must be an array');
        }

        return Helper::addTrailingSpaces($suggestions);
    }
}
