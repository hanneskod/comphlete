<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\Matcher;

use hanneskod\comphlete\Matcher\OptionMatcher;
use hanneskod\comphlete\Matcher\MatcherAwareInterface;
use hanneskod\comphlete\Matcher\MatcherInterface;
use hanneskod\comphlete\Matcher\NullMatcher;
use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OptionMatcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(new Dictionary);
        $this->shouldHaveType(OptionMatcher::class);
    }

    function it_is_a_matcher()
    {
        $this->beConstructedWith(new Dictionary);
        $this->shouldHaveType(MatcherInterface::class);
    }

    function it_completes()
    {
        $this->beConstructedWith(new Dictionary(new Word('foo'), new Word('bar')));
        $this->complete(new Word('f'))->shouldReturnDictionary(new Dictionary(new Word('foo')));
    }

    function it_does_not_complete_when_trailing_value_is_present()
    {
        $this->beConstructedWith(new Dictionary(new Word('--foo')));
        $this->complete(new Word('--foo=value'))->shouldReturnDictionary(new Dictionary);
    }

    function it_ignores_trailing_value_when_matching()
    {
        $this->beConstructedWith(new Dictionary(new Word('--foo')));
        $this->matches(new Word('--foo=value'))->shouldReturn(true);
    }

    function it_returns_null_matcher_if_next_matcher_is_not_set()
    {
        $this->beConstructedWith(new Dictionary(new Word('foo')));
        $this->getNextMatcher(new Word('foo'))->shouldHaveType(NullMatcher::class);
    }

    function it_returns_next_matcher(MatcherInterface $next)
    {
        $this->beConstructedWith(new Dictionary(new Word('foo')));
        $this->setMatcher($next);
        $this->getNextMatcher(new Word('foo'))->shouldReturn($next);
    }

    function it_returns_null_matcher_if_no_match(MatcherInterface $next)
    {
        $this->beConstructedWith(new Dictionary(new Word('foo')));
        $this->getNextMatcher(new Word('bar'))->shouldHaveType(NullMatcher::class);
    }
}
