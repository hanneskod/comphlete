<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\Suggester;

use hanneskod\comphlete\Suggester\NullSuggester;
use hanneskod\comphlete\LineParser\Node;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NullSuggesterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(NullSuggester::class);
    }

    function it_handles_node(Node $node)
    {
        $this->handles($node)->shouldReturn(true);
    }

    function it_suggests_nothing(Node $node)
    {
        $this->getSuggestions($node)->shouldReturn([]);
    }
}
