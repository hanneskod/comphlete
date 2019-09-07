<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\Suggester;

use hanneskod\comphlete\LineParser\Node;

final class NullSuggester implements SuggesterInterface
{
    public function handles(Node $node): bool
    {
        return true;
    }

    public function getSuggestions(Node $node): array
    {
        return [];
    }
}
