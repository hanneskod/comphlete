<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\Matcher;

use hanneskod\comphlete\Matcher\ArgumentsAndOptionsMatcher;
use hanneskod\comphlete\Matcher\MatcherAwareInterface;
use hanneskod\comphlete\Matcher\MatcherInterface;
use hanneskod\comphlete\Matcher\NullMatcher;
use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArgumentsAndOptionsMatcherSpec extends ObjectBehavior
{
    function let(MatcherInterface $argsMatcher, MatcherInterface $optsMatcher)
    {
        $this->beConstructedWith($argsMatcher, $optsMatcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ArgumentsAndOptionsMatcher::class);
    }

    function it_is_a_matcher()
    {
        $this->shouldHaveType(MatcherInterface::class);
    }

    function it_completes_options($optsMatcher)
    {
        $word = new Word('-');
        $optsMatcher->complete($word)->willReturn(new Dictionary(new Word('--foo')));
        $this->complete($word)->shouldReturnDictionary(new Dictionary(new Word('--foo')));
    }

    function it_completes_arguments($argsMatcher)
    {
        $word = new Word('foo');
        $argsMatcher->complete($word)->willReturn(new Dictionary($word));
        $this->complete($word)->shouldReturnDictionary(new Dictionary($word));
    }

    function it_returns_self_if_options_match($optsMatcher)
    {
        $word = new Word('-foo');
        $optsMatcher->matches($word)->willReturn(true);
        $this->getNextMatcher($word)->shouldReturn($this->getWrappedObject());
    }

    function it_develops_args_if_args_match($argsMatcher, $optsMatcher, MatcherInterface $next)
    {
        $word = new Word('foo');
        $optsMatcher->matches($word)->willReturn(false);
        $argsMatcher->matches($word)->willReturn(true);
        $argsMatcher->getNextMatcher($word)->willReturn($next);
        $this->getNextMatcher($word)->shouldReturn($this->getWrappedObject());
        $next->complete($word)->willReturn(new Dictionary(new Word('next-matcher')))->shouldBeCalled();
        $this->complete($word)->shouldReturnDictionary(new Dictionary(new Word('next-matcher')));
    }

    function it_returns_null_matcher_if_nothing_matches($argsMatcher, $optsMatcher)
    {
        $word = new Word('foo');
        $optsMatcher->matches($word)->willReturn(false);
        $argsMatcher->matches($word)->willReturn(false);
        $this->getNextMatcher($word)->shouldHaveType(NullMatcher::class);
    }

    function it_defaults_to_no_match($argsMatcher, $optsMatcher)
    {
        $word = new Word('foo');
        $argsMatcher->matches($word)->willReturn(false);
        $optsMatcher->matches($word)->willReturn(false);
        $this->matches($word)->shouldReturn(false);
    }

    function it_matches_if_args_matches($argsMatcher, $optsMatcher)
    {
        $word = new Word('foo');
        $argsMatcher->matches($word)->willReturn(true);
        $optsMatcher->matches($word)->willReturn(false);
        $this->matches($word)->shouldReturn(true);
    }

    function it_matches_if_opts_matches($argsMatcher, $optsMatcher)
    {
        $word = new Word('-foo');
        $argsMatcher->matches($word)->willReturn(false);
        $optsMatcher->matches($word)->willReturn(true);
        $this->matches($word)->shouldReturn(true);
    }
}
