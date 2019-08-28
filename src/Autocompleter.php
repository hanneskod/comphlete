<?php

declare(strict_types = 1);

namespace hanneskod\comphlete;

use hanneskod\comphlete\Input\InputInterface;
use hanneskod\comphlete\Matcher\MatcherInterface;

final class Autocompleter
{
    private $matcher;

    public function __construct(MatcherInterface $matcher)
    {
        $this->matcher = $matcher;
    }

    public function complete(InputInterface $input): Dictionary
    {
        $matcher = $this->matcher;

        foreach ($input->context() as $word) {
            $matcher = $matcher->getNextMatcher($word);
        }

        return $matcher->complete($input->current());
    }
}
