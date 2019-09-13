<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\Suggester;

use hanneskod\comphlete\LineParser\Node;
use hanneskod\comphlete\LineParser\Argument;
use hanneskod\comphlete\Helper;

final class ArgumentSuggester implements SuggesterInterface
{
    /** @var array */
    private $argumentMap;

    public function __construct(array $argumentMap)
    {
        $this->argumentMap = $argumentMap;
    }

    public function handles(Node $node): bool
    {
        return $node instanceof Argument && isset($this->argumentMap[$node->getArgumentNr()]);
    }

    public function getSuggestions(Node $node): array
    {
        if (!$node instanceof Argument) {
            throw new \InvalidArgumentException('Node must be Argument');
        }

        $suggestions = $this->argumentMap[$node->getArgumentNr()] ?? [];

        if (is_callable($suggestions)) {
            $suggestions = $suggestions($node);
        }

        if (!is_array($suggestions)) {
            throw new \UnexpectedValueException('Suggstions must be an array');
        }

        return Helper::addTrailingSpaces($suggestions);
    }
}
