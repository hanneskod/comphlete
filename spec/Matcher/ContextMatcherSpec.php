<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\Matcher;

use hanneskod\comphlete\Matcher\ContextMatcher;
use hanneskod\comphlete\Matcher\MatcherInterface;
use hanneskod\comphlete\Matcher\NullMatcher;
use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContextMatcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ContextMatcher::class);
    }

    function it_is_a_matcher()
    {
        $this->shouldHaveType(MatcherInterface::class);
    }

    function it_defaults_to_null_matcher()
    {
        $this->getNextMatcher(new Word('foo'))->shouldHaveType(NullMatcher::class);
    }

    function it_fins_next_matcher(MatcherInterface $matcher)
    {
        $this->beConstructedWith([
            'foo' => $matcher,
        ]);

        $this->getNextMatcher(new Word('foo'))->shouldReturn($matcher);
    }

    function it_matches(MatcherInterface $matcher)
    {
        $this->beConstructedWith([
            'foo' => $matcher,
            'bar' => $matcher,
        ]);

        $this->matches(new Word('foo'))->shouldReturn(true);
    }

    function it_completes_single_match(MatcherInterface $matcher)
    {
        $this->beConstructedWith([
            'foo' => $matcher,
            'bar' => $matcher,
        ]);

        $this->complete(new Word('foo'))->shouldReturnDictionary(new Dictionary(new Word('foo')));
    }

    function it_completes_multiple_matches(MatcherInterface $matcher)
    {
        $this->beConstructedWith([
            'foo' => $matcher,
            'bar' => $matcher,
            'baz' => $matcher,
        ]);

        $this->complete(new Word('ba'))->shouldReturnDictionary(new Dictionary(new Word('bar'), new Word('baz')));
    }
}
