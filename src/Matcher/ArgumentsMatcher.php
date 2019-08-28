<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\Matcher;

use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;

final class ArgumentsMatcher implements MatcherInterface
{
    /** @var MatcherInterface */
    private $currentMatcher;

    /** @var MatcherInterface[] */
    private $matchers;

    public function __construct(MatcherInterface ...$matchers)
    {
        $this->matchers = $matchers;
        $this->loadNextMatcher();
    }

    public function getNextMatcher(Word $word): MatcherInterface
    {
        if ($this->matches($word)) {
            $this->loadNextMatcher();
            return $this;
        }

        return new NullMatcher;
    }

    public function complete(Word $word): Dictionary
    {
        return $this->currentMatcher->complete($word);
    }

    public function matches(Word $word): bool
    {
        return $this->currentMatcher->matches($word);
    }

    private function loadNextMatcher()
    {
        $this->currentMatcher = array_shift($this->matchers) ?: new NullMatcher;

        if ($this->currentMatcher instanceof MatcherAwareInterface) {
            $this->currentMatcher->setMatcher($this->matchers[0] ?? new NullMatcher);
        }
    }
}
