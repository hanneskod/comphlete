<?php

namespace hanneskod\comphlete\Suggester;

use hanneskod\comphlete\LineParser\Node;

interface SuggesterInterface
{
    /**
     * Check if this suggester handles node type
     */
    public function handles(Node $node): bool;

    /**
     * Get suggestions for node
     *
     * @return string[]
     */
    public function getSuggestions(Node $node): array;
}
