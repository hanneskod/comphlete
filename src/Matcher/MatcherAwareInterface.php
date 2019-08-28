<?php

namespace hanneskod\comphlete\Matcher;

interface MatcherAwareInterface
{
    public function setMatcher(MatcherInterface $matcher): void;
}
