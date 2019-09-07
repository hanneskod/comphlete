<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\LineParser;

use hanneskod\comphlete\LineParser\Tree;
use hanneskod\comphlete\LineParser\Argument;
use hanneskod\comphlete\LineParser\Node;
use hanneskod\comphlete\LineParser\Space;
use PhpSpec\ObjectBehavior;

class TreeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Tree::class);
    }

    function it_can_get_node_from_low_offset()
    {
        $foo = new Node('foo');
        $bar = new Node('bar');
        $this->beConstructedWith($foo, $bar, $foo);
        $this->getNodeAt(3)->shouldReturn($bar);
    }

    function it_can_get_node_from_high_offset()
    {
        $foo = new Node('foo');
        $bar = new Node('bar');
        $this->beConstructedWith($foo, $bar, $foo);
        $this->getNodeAt(5)->shouldReturn($bar);
    }

    function it_gets_first_node_on_underflow()
    {
        $foo = new Node('foo');
        $this->beConstructedWith($foo);
        $this->getNodeAt(-1)->shouldBeLike($foo);
    }

    function it_gets_next_argument_on_underflow_in_empty_tree()
    {
        $this->getNodeAt(-1)->shouldBeLike(new Argument(0, ''));
    }

    function it_gets_next_argument_if_tree_is_only_space()
    {
        $this->beConstructedWith(new Space('  '));
        $this->getNodeAt(0)->shouldBeLike(new Argument(0, ''));
    }

    function it_gets_previous_node_on_single_space()
    {
        $foo = new Node('foo');
        $this->beConstructedWith($foo, new Space(' '));
        $this->getNodeAt(3)->shouldReturn($foo);
    }

    function it_gets_next_argument_on_multiple_space()
    {
        $this->beConstructedWith(new Argument(0, 'foo'), new Space('  '));
        $this->getNodeAt(4)->shouldBeLike(new Argument(1, ''));
    }

    function it_gets_previous_node_on_overflow_with_content_on_last_pos()
    {
        $foo = new Node('foo');
        $this->beConstructedWith($foo);
        $this->getNodeAt(3)->shouldReturn($foo);
    }

    function it_gets_next_argument_on_overflow_with_space_on_last_pos()
    {
        $this->beConstructedWith(new Node('foo'), new Space(' '));
        $this->getNodeAt(4)->shouldBeLike(new Argument(0, ''));
    }

    function it_can_get_first_argument()
    {
        $arg = new Argument(0, 'bar');
        $this->beConstructedWith(new Node('foo'), new Space(' '), $arg);
        $this->getFirstArgument()->shouldReturn($arg);
    }

    function it_defaults_to_empty_first_argument()
    {
        $this->getFirstArgument()->shouldBeLike(new Argument(0, ''));
    }

    function it_can_get_concrete_nodes()
    {
        $foo = new Node('foo');
        $bar = new Node('bar');
        $this->beConstructedWith($foo, new Space(' '), $bar);
        $this->getConcreteNodes()->shouldReturn([$foo, $bar]);
    }
}
