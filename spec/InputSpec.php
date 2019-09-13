<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete;

use hanneskod\comphlete\Input;
use hanneskod\comphlete\LineParser\Tree;
use hanneskod\comphlete\LineParser\Node;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InputSpec extends ObjectBehavior
{
    function let(Tree $tree)
    {
        $this->beConstructedWith($tree, 0, '');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Input::class);
    }

    function it_can_get_tree($tree)
    {
        $this->getSyntaxTree()->shouldReturn($tree);
    }

    function it_can_get_current_node($tree, Node $node)
    {
        $this->beConstructedWith($tree, 1, '');
        $tree->getNodeAt(1)->willReturn($node);
        $this->getCurrentNode()->shouldReturn($node);
    }

    function it_can_get_word_to_replace($tree)
    {
        $this->beConstructedWith($tree, 0, 'foobar');
        $this->getWordToReplace()->shouldReturn('foobar');
    }
}
