<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\Matcher;

use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;

final class ArgumentsAndOptionsMatcher implements MatcherInterface
{
    /** @var MatcherInterface */
    private $argsMatcher;

    /** @var MatcherInterface */
    private $optsMatcher;

    /** @var Word */
    private $optFlag;

    public function __construct(MatcherInterface $argsMatcher, MatcherInterface $optsMatcher)
    {
        $this->argsMatcher = $argsMatcher;
        $this->optsMatcher = $optsMatcher;
        $this->optFlag = new Word('-');
    }

    public function getNextMatcher(Word $word): MatcherInterface
    {
        if ($word->startsWith($this->optFlag)) {
            return $this->optsMatcher->matches($word) ? $this: new NullMatcher;
        }

        if ($this->argsMatcher->matches($word)) {
            $this->argsMatcher = $this->argsMatcher->getNextMatcher($word);
            return $this;
        }

        return new NullMatcher;
    }

    public function complete(Word $word): Dictionary
    {
        if ($word->startsWith($this->optFlag)) {
            return $this->optsMatcher->complete($word);
        }

        return $this->argsMatcher->complete($word);
    }

    public function matches(Word $word): bool
    {
        if ($word->startsWith($this->optFlag)) {
            return $this->optsMatcher->matches($word);
        }

        return $this->argsMatcher->matches($word);
    }
}
