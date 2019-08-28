<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\Matcher;

use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;

trait NextMatcherTrait
{
    /** @var MatcherInterface */
    private $nextMatcher;

    public function setMatcher(MatcherInterface $matcher): void
    {
        $this->nextMatcher = $matcher;
    }

    public function getNextMatcher(Word $word): MatcherInterface
    {
        if (isset($this->nextMatcher) && $this->matches($word)) {
            return $this->nextMatcher;
        }

        return new NullMatcher;
    }
}
