<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\Matcher;

use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;

final class OptionMatcher implements MatcherInterface, MatcherAwareInterface
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
        if (preg_match('/([^=]+)(\=.*)$/', (string)$toMatch)) {
            return new Dictionary;
        }

        $completions = [];

        foreach ($this->dictionary as $word) {
            if ($word->startsWith($toMatch)) {
                $completions[] = $word;
            }
        }

        return new Dictionary(...$completions);
    }

    public function matches(Word $toMatch): bool
    {
        if (preg_match('/([^=]+)(\=.*)$/', (string)$toMatch, $matches)) {
            $toMatch = new Word($matches[1]);
        }

        $completions = [];

        foreach ($this->dictionary as $word) {
            if ($word->startsWith($toMatch)) {
                $completions[] = $word;
            }
        }

        return (new Dictionary(...$completions))->is($toMatch);
    }
}
