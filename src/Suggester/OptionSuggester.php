<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\Suggester;

use hanneskod\comphlete\LineParser\Node;
use hanneskod\comphlete\LineParser\Option;
use hanneskod\comphlete\Helper;

final class OptionSuggester implements SuggesterInterface
{
    /** @var array|callable */
    private $suggestions;

    /**
     * @param array|callable $suggestions Any callable passed must return an array
     */
    public function __construct($suggestions)
    {
        if (!is_array($suggestions) && !is_callable($suggestions)) {
            throw new \InvalidArgumentException('Suggestions must be an array or a callable returning an array');
        }

        $this->suggestions = $suggestions;
    }

    public function handles(Node $node): bool
    {
        return $node instanceof Option;
    }

    public function getSuggestions(Node $node): array
    {
        $suggestions = $this->suggestions;

        if (is_callable($suggestions)) {
            $suggestions = ($suggestions)($node);
        }

        if (!is_array($suggestions)) {
            throw new \UnexpectedValueException('Suggester callable must return an array');
        }

        return Helper::filter($suggestions, $node->getValue());
    }
}
