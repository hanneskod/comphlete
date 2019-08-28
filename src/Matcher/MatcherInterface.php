<?php

namespace hanneskod\comphlete\Matcher;

use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;

interface MatcherInterface
{
    public function getNextMatcher(Word $word): MatcherInterface;

    public function matches(Word $word): bool;

    public function complete(Word $word): Dictionary;
}
