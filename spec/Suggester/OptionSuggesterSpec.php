<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\Suggester;

use hanneskod\comphlete\Suggester\OptionSuggester;
use hanneskod\comphlete\LineParser\Node;
use hanneskod\comphlete\LineParser\Option;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OptionSuggesterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith([]);
        $this->shouldHaveType(OptionSuggester::class);
    }

    function it_handles_options()
    {
        $this->beConstructedWith([]);
        $this->handles(new Option(''))->shouldReturn(true);
    }

    function it_returns_suggestions_from_array()
    {
        $this->beConstructedWith(['foo', 'bar', 'baz']);
        $this->getSuggestions(new Option('b'))->shouldReturn(['bar', 'baz']);
    }

    function it_returns_suggestions_from_callable()
    {
        $this->beConstructedWith(function () {
            return ['foo', 'bar', 'baz'];
        });
        $this->getSuggestions(new Option('b'))->shouldReturn(['bar', 'baz']);
    }

    function it_throws_if_callable_does_not_return_array()
    {
        $this->beConstructedWith(function () {
            return null;
        });
        $this->shouldThrow(\UnexpectedValueException::class)->duringGetSuggestions(new Option('b'));
    }

    function it_throws_if_suggestions_is_invalid()
    {
        $this->beConstructedWith(null);
        $this->shouldThrow(\InvalidArgumentException::class)->duringInstantiation();
    }
}
