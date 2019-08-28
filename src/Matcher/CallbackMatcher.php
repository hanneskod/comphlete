<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\Matcher;

use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;

final class CallbackMatcher implements MatcherInterface, MatcherAwareInterface
{
    use NextMatcherTrait;

    /** @var callable */
    private $callback;

    public function __construct(callable $callback)
    {
        $this->callback = $callback;
    }

    public function complete(Word $toMatch): Dictionary
    {
        $dictionary = ($this->callback)($toMatch);

        if (!$dictionary instanceof Dictionary) {
            throw new \LogicException('Callback must return a dictionary object');
        }

        $matches = [];

        foreach ($dictionary as $word) {
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
