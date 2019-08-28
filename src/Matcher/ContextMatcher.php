<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\Matcher;

use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;

final class ContextMatcher implements MatcherInterface
{
    /** @var MatcherInterface[] */
    private $matchers;

    /**
     * @param MatcherInterface[] $matchers
     */
    public function __construct(array $matchers = [])
    {
        $this->matchers = $matchers;
    }

    public function getNextMatcher(Word $word): MatcherInterface
    {
        return $this->matchers[(string)$word] ?? new NullMatcher;
    }

    public function complete(Word $word): Dictionary
    {
        $matches = [];

        foreach ($this->matchers as $key => $matcher) {
            $key = new Word($key);
            if ($key->startsWith($word)) {
                $matches[] = $key;
            }
        }

        return new Dictionary(...$matches);
    }

    public function matches(Word $word): bool
    {
        return $this->complete($word)->is($word);
    }
}
