<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\Suggester;

use hanneskod\comphlete\Suggester\OptionValueSuggester;
use hanneskod\comphlete\LineParser\Node;
use hanneskod\comphlete\LineParser\OptionValue;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OptionValueSuggesterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith([]);
        $this->shouldHaveType(OptionValueSuggester::class);
    }

    function it_does_not_handle_unspecified_nodes()
    {
        $this->beConstructedWith([]);
        $this->handles(new OptionValue('--opt', ''))->shouldReturn(false);
    }

    function it_handles_specified_nodes()
    {
        $this->beConstructedWith(['--opt' => []]);
        $this->handles(new OptionValue('--opt', ''))->shouldReturn(true);
    }

    function it_returns_suggestions_from_array()
    {
        $this->beConstructedWith(['--opt' => ['foo', 'bar', 'baz']]);
        $this->getSuggestions(new OptionValue('--opt', 'b'))->shouldReturn(['bar', 'baz']);
    }

    function it_returns_empty_array_if_opt_is_not_specified()
    {
        $this->beConstructedWith([]);
        $this->getSuggestions(new OptionValue('--opt', 'b'))->shouldReturn([]);
    }

    function it_throws_if_suggestions_is_invalid()
    {
        $this->beConstructedWith(['--opt' => 'not-an-array-or-callable']);
        $this->shouldThrow(\UnexpectedValueException::class)->duringGetSuggestions(new OptionValue('--opt', 'b'));
    }

    function it_returns_suggestions_from_callable()
    {
        $this->beConstructedWith(['--opt' => function () {
            return ['foo', 'bar', 'baz'];
        }]);
        $this->getSuggestions(new OptionValue('--opt', 'b'))->shouldReturn(['bar', 'baz']);
    }

    function it_throws_if_callable_does_not_return_array()
    {
        $this->beConstructedWith(['--opt' => function () {
            return null;
        }]);
        $this->shouldThrow(\UnexpectedValueException::class)->duringGetSuggestions(new OptionValue('--opt', 'b'));
    }

    function it_throws_if_a_non_option_value_is_used()
    {
        $this->beConstructedWith([]);
        $this->shouldThrow(\InvalidArgumentException::class)->duringGetSuggestions(new Node(''));
    }
}
