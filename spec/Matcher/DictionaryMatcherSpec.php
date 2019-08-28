<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\Matcher;

use hanneskod\comphlete\Matcher\DictionaryMatcher;
use hanneskod\comphlete\Matcher\MatcherAwareInterface;
use hanneskod\comphlete\Matcher\MatcherInterface;
use hanneskod\comphlete\Matcher\NullMatcher;
use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DictionaryMatcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(new Dictionary);
        $this->shouldHaveType(DictionaryMatcher::class);
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

    function it_matches()
    {
        $this->beConstructedWith(new Dictionary(new Word('foo'), new Word('bar')));
        $this->matches(new Word('foo'))->shouldReturn(true);
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
