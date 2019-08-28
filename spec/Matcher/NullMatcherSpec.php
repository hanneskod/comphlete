<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\Matcher;

use hanneskod\comphlete\Matcher\NullMatcher;
use hanneskod\comphlete\Matcher\MatcherInterface;
use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NullMatcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(NullMatcher::class);
    }

    function it_is_a_matcher()
    {
        $this->shouldHaveType(MatcherInterface::class);
    }

    function it_always_returns_null_matcher()
    {
        $this->getNextMatcher(new Word(''))->shouldHaveType(NullMatcher::class);
    }

    function it_always_matches_empty_dictionary()
    {
        $this->complete(new Word(''))->shouldReturnDictionary(new Dictionary);
    }

    function it_never_matches()
    {
        $this->matches(new Word(''))->shouldReturn(false);
    }
}
