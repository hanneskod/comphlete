<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\Matcher;

use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;

final class DictionaryMatcher implements MatcherInterface, MatcherAwareInterface
{
    use NextMatcherTrait;

    /** @var Dictionary */
    private $dictionary;

    public function __construct(Dictionary $dictionary)
    {
        $this->dictionary = $dictionary;
    }

    public function complete(Word $toMatch): Dictionary
    {
        $matches = [];

        foreach ($this->dictionary as $word) {
            if ($word->startsWith($toMatch)) {
                $matches[] = $word;
            }
        }

        return new Dictionary(...$matches);
    }

    public function matches(Word $word): bool
    {
        return $this->complete($word)->is($word);
    }
}
