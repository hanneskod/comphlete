<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\Matcher;

use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;

final class NullMatcher implements MatcherInterface
{
    public function getNextMatcher(Word $word): MatcherInterface
    {
        return $this;
    }

    public function complete(Word $word): Dictionary
    {
        return new Dictionary;
    }

    public function matches(Word $word): bool
    {
        return false;
    }
}
