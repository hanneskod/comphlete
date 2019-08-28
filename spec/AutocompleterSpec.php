<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete;

use hanneskod\comphlete\Autocompleter;
use hanneskod\comphlete\Input\InputInterface;
use hanneskod\comphlete\Matcher\MatcherInterface;
use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AutocompleterSpec extends ObjectBehavior
{
    function let(MatcherInterface $matcher)
    {
        $this->beConstructedWith($matcher);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Autocompleter::class);
    }

    function it_completes($matcher, InputInterface $input)
    {
        $foo = new Word('foo');
        $bar = new Word('bar');

        $input->context()->willReturn(new Dictionary($foo));
        $input->current()->willReturn($bar);

        $matcher->getNextMatcher($foo)->willReturn($matcher)->shouldBeCalled();

        $matcher->complete($bar)->willReturn(new Dictionary($bar));

        $this->complete($input)->shouldReturnDictionary(new Dictionary($bar));
    }
}
