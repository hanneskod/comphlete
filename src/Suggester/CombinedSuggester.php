<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\Suggester;

use hanneskod\comphlete\LineParser\Node;

final class CombinedSuggester implements SuggesterInterface
{
    /** @var SuggesterInterface[] */
    private $suggesters;

    public function __construct(SuggesterInterface ...$suggesters)
    {
        $this->suggesters = $suggesters;
    }

    public function handles(Node $node): bool
    {
        foreach ($this->suggesters as $suggester) {
            if ($suggester->handles($node)) {
                return true;
            }
        }

        return false;
    }

    public function getSuggestions(Node $node): array
    {
        foreach ($this->suggesters as $suggester) {
            if ($suggester->handles($node)) {
                return $suggester->getSuggestions($node);
            }
        }

        return [];
    }
}
