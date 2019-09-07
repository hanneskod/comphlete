<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\LineParser;

use hanneskod\comphlete\LineParser\LineParser;
use hanneskod\comphlete\LineParser\Argument;
use hanneskod\comphlete\LineParser\EndOfOptions;
use hanneskod\comphlete\LineParser\Node;
use hanneskod\comphlete\LineParser\NonPrintable;
use hanneskod\comphlete\LineParser\Tree;
use hanneskod\comphlete\LineParser\Option;
use hanneskod\comphlete\LineParser\OptionAssignmentOperator;
use hanneskod\comphlete\LineParser\OptionValue;
use hanneskod\comphlete\LineParser\Space;
use PhpSpec\ObjectBehavior;

class LineParserSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(LineParser::class);
    }

    function it_can_parse_empty_string()
    {
        $this->parse('')->shouldBeLike(
            new Tree()
        );
    }

    function it_can_parse_void()
    {
        $this->parse(' ')->shouldBeLike(
            new Tree(
                new Space(' ')
            )
        );
    }

    function it_can_parse_command_name()
    {
        $this->parse('cmd')->shouldBeLike(
            new Tree(
                new Node('cmd')
            )
        );
    }

    function it_can_parse_escaped_chars()
    {
        $this->parse("foo\ bar")->shouldBeLike(
            new Tree(
                new Node('foo\ bar')
            )
        );
    }

    function it_can_parse_arguments()
    {
        $this->parse('c arg1 arg2')->shouldBeLike(
            new Tree(
                new Node('c'),
                new Space(' '),
                new Argument(0, 'arg1'),
                new Space(' '),
                new Argument(1, 'arg2')
            )
        );
    }

    function it_can_parse_short_option()
    {
        $this->parse('c -af')->shouldBeLike(
            new Tree(
                new Node('c'),
                new Space(' '),
                new Option('-af')
            )
        );
    }

    function it_can_parse_escaped_short_option()
    {
        $this->parse('c -\ ')->shouldBeLike(
            new Tree(
                new Node('c'),
                new Space(' '),
                new Option('-\ ')
            )
        );
    }

    function it_can_parse_long_option()
    {
        $this->parse('c --foo')->shouldBeLike(
            new Tree(
                new Node('c'),
                new Space(' '),
                new Option('--foo')
            )
        );
    }

    function it_can_parse_long_option_with_escaped_name()
    {
        $this->parse('c --foo\ bar')->shouldBeLike(
            new Tree(
                new Node('c'),
                new Space(' '),
                new Option('--foo\ bar')
            )
        );
    }

    function it_can_parse_long_option_with_equal_sign()
    {
        $this->parse('c --foo=')->shouldBeLike(
            new Tree(
                new Node('c'),
                new Space(' '),
                new Option('--foo'),
                new OptionAssignmentOperator('='),
                new OptionValue('--foo', '')
            )
        );
    }

    function it_can_parse_long_option_with_value()
    {
        $this->parse('c --foo=bar')->shouldBeLike(
            new Tree(
                new Node('c'),
                new Space(' '),
                new Option('--foo'),
                new OptionAssignmentOperator('='),
                new OptionValue('--foo', 'bar')
            )
        );
    }

    function it_can_parse_long_option_with_spaces_before_value()
    {
        $this->parse('c --foo = bar')->shouldBeLike(
            new Tree(
                new Node('c'),
                new Space(' '),
                new Option('--foo'),
                new Space(' '),
                new OptionAssignmentOperator('='),
                new Space(' '),
                new OptionValue('--foo', 'bar')
            )
        );
    }

    function it_can_parse_long_option_with_escaped_value()
    {
        $this->parse('c --foo=bar\ baz')->shouldBeLike(
            new Tree(
                new Node('c'),
                new Space(' '),
                new Option('--foo'),
                new OptionAssignmentOperator('='),
                new OptionValue('--foo', 'bar\ baz')
            )
        );
    }

    function it_can_parse_end_of_options()
    {
        $this->parse('c -- ')->shouldBeLike(
            new Tree(
                new Node('c'),
                new Space(' '),
                new EndOfOptions('--'),
                new Space(' ')
            )
        );
    }

    function it_can_parse_trailing_arguments()
    {
        $this->parse('c -- --foo')->shouldBeLike(
            new Tree(
                new Node('c'),
                new Space(' '),
                new EndOfOptions('--'),
                new Space(' '),
                new Argument(0, '--foo')
            )
        );
    }

    function it_can_parse_multiple_end_of_options()
    {
        $this->parse('c -- -- ')->shouldBeLike(
            new Tree(
                new Node('c'),
                new Space(' '),
                new EndOfOptions('--'),
                new Space(' '),
                new Argument(0, '--'),
                new Space(' ')
            )
        );
    }
}
