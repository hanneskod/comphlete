<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\Matcher;

use hanneskod\comphlete\Matcher\CallbackMatcher;
use hanneskod\comphlete\Matcher\MatcherAwareInterface;
use hanneskod\comphlete\Matcher\MatcherInterface;
use hanneskod\comphlete\Matcher\NullMatcher;
use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CallbackMatcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(function () {});
        $this->shouldHaveType(CallbackMatcher::class);
    }

    function it_is_a_matcher()
    {
        $this->beConstructedWith(function () {});
        $this->shouldHaveType(MatcherInterface::class);
    }

    function it_fails_if_callable_does_not_return_a_dictionary()
    {
        $this->beConstructedWith(function () {
            return 'this-is-not-a-dictionary';
        });

        $this->shouldThrow(\LogicException::class)->duringComplete(new Word(''));
    }

    function it_completes()
    {
        $this->beConstructedWith(function () {
            return new Dictionary(new Word('foo'), new Word('bar'));
        });

        $this->complete(new Word('foo'))->shouldReturnDictionary(new Dictionary(new Word('foo')));
    }

    function it_matches()
    {
        $this->beConstructedWith(function () {
            return new Dictionary(new Word('foo'), new Word('bar'));
        });

        $this->matches(new Word('foo'))->shouldReturn(true);
    }

    function it_returns_null_matcher_if_next_matcher_is_not_set()
    {
        $this->beConstructedWith(function () {
            return new Dictionary(new Word('foo'));
        });

        $this->getNextMatcher(new Word('foo'))->shouldHaveType(NullMatcher::class);
    }

    function it_returns_next_matcher(MatcherInterface $next)
    {
        $this->beConstructedWith(function () {
            return new Dictionary(new Word('foo'));
        });

        $this->setMatcher($next);

        $this->getNextMatcher(new Word('foo'))->shouldReturn($next);
    }

    function it_returns_null_matcher_if_no_match(MatcherInterface $next)
    {
        $this->beConstructedWith(function () {
            return new Dictionary(new Word('foo'));
        });

        $this->getNextMatcher(new Word('bar'))->shouldHaveType(NullMatcher::class);
    }
}
