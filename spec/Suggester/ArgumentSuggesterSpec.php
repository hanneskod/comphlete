<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\Suggester;

use hanneskod\comphlete\Suggester\ArgumentSuggester;
use hanneskod\comphlete\LineParser\Node;
use hanneskod\comphlete\LineParser\Argument;
use PhpSpec\ObjectBehavior;

class ArgumentSuggesterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith([]);
        $this->shouldHaveType(ArgumentSuggester::class);
    }

    function it_does_not_handle_unspecified_nodes()
    {
        $this->beConstructedWith([]);
        $this->handles(new Argument(0, ''))->shouldReturn(false);
    }

    function it_handles_specified_nodes()
    {
        $this->beConstructedWith([0 => []]);
        $this->handles(new Argument(0, ''))->shouldReturn(true);
    }

    function it_returns_suggestions_from_array()
    {
        $this->beConstructedWith([0 => ['foo', 'bar', 'baz']]);
        $this->getSuggestions(new Argument(0, 'b'))->shouldReturn(['foo ', 'bar ', 'baz ']);
    }

    function it_returns_empty_array_if_opt_is_not_specified()
    {
        $this->beConstructedWith([]);
        $this->getSuggestions(new Argument(0, 'b'))->shouldReturn([]);
    }

    function it_throws_if_suggestions_is_invalid()
    {
        $this->beConstructedWith([1 => 'not-an-array-or-callable']);
        $this->shouldThrow(\UnexpectedValueException::class)->duringGetSuggestions(new Argument(1, ''));
    }

    function it_returns_suggestions_from_callable()
    {
        $this->beConstructedWith([1 => function () {
            return ['foo', 'bar', 'baz'];
        }]);
        $this->getSuggestions(new Argument(1, 'b'))->shouldReturn(['foo ', 'bar ', 'baz ']);
    }

    function it_throws_if_callable_does_not_return_array()
    {
        $this->beConstructedWith([0 => function () {
            return null;
        }]);
        $this->shouldThrow(\UnexpectedValueException::class)->duringGetSuggestions(new Argument(0, ''));
    }

    function it_throws_if_a_non_option_value_is_used()
    {
        $this->beConstructedWith([]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringGetSuggestions(new Node(''));
    }
}
