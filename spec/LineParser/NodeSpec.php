<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\LineParser;

use hanneskod\comphlete\LineParser\Node;
use PhpSpec\ObjectBehavior;

class NodeSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('');
        $this->shouldHaveType(Node::class);
    }

    function it_can_get_raw_value()
    {
        $this->beConstructedWith('foo\\ bar');
        $this->getRawValue()->shouldReturn('foo\ bar');
    }

    function it_can_get_value_with_slashes_removed()
    {
        $this->beConstructedWith('foo\\ bar');
        $this->getValue()->shouldReturn('foo bar');
    }

    function it_keeps_escaped_backslash()
    {
        $this->beConstructedWith('\\\\');
        $this->getValue()->shouldReturn('\\');
    }

    function it_keeps_backslash_at_end_of_string()
    {
        $this->beConstructedWith('\\');
        $this->getValue()->shouldReturn('\\');
    }
}
