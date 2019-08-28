<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\Matcher;

use hanneskod\comphlete\Matcher\ArgumentsMatcher;
use hanneskod\comphlete\Matcher\MatcherAwareInterface;
use hanneskod\comphlete\Matcher\MatcherInterface;
use hanneskod\comphlete\Matcher\NullMatcher;
use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ArgumentsMatcherSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ArgumentsMatcher::class);
    }

    function it_is_a_matcher()
    {
        $this->shouldHaveType(MatcherInterface::class);
    }

    function it_defaults_to_no_matching()
    {
        $this->complete(new Word('foo'))->shouldReturnDictionary(new Dictionary);
    }

    function it_completes_using_matcher(MatcherInterface $matcher)
    {
        $this->beConstructedWith($matcher);

        $dictionary = new Dictionary;
        $word = new Word('');

        $matcher->complete($word)->willReturn($dictionary);

        $this->complete($word)->shouldReturn($dictionary);
    }

    function it_matches_using_matcher(MatcherInterface $matcher)
    {
        $this->beConstructedWith($matcher);

        $word = new Word('');

        $matcher->matches($word)->willReturn(true)->shouldBeCalled();

        $this->matches($word)->shouldReturn(true);
    }

    function it_loades_next_matcher(MatcherInterface $first, MatcherInterface $second)
    {
        $this->beConstructedWith($first, $second);

        $foo = new Word('foo');

        $first->matches($foo)->willReturn(true);
        $second->complete($foo)->willReturn(new Dictionary($foo));

        $this->getNextMatcher($foo)->shouldReturn($this);

        $this->complete($foo)->shouldReturnDictionary(new Dictionary($foo));
    }

    function it_breaks_the_chain_on_no_match(MatcherInterface $matcher)
    {
        $this->beConstructedWith($matcher);

        $word = new Word('foo');

        $matcher->matches($word)->willReturn(false);

        $this->getNextMatcher($word)->shouldHaveType(NullMatcher::class);
    }
}
