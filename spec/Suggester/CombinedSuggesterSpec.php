<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\Suggester;

use hanneskod\comphlete\Suggester\CombinedSuggester;
use hanneskod\comphlete\Suggester\SuggesterInterface;
use hanneskod\comphlete\LineParser\Node;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CombinedSuggesterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(CombinedSuggester::class);
    }

    function it_defaults_to_not_handles(Node $node)
    {
        $this->handles($node)->shouldReturn(false);
    }

    function it_handles_if_loaded_suggester_handles(Node $node, SuggesterInterface $suggester)
    {
        $this->beConstructedWith($suggester);
        $suggester->handles($node)->willReturn(true);
        $this->handles($node)->shouldReturn(true);
    }

    function it_defaults_to_no_suggestions(Node $node)
    {
        $this->getSuggestions($node)->shouldReturn([]);
    }

    function it_suggests_from_loaded_suggester(Node $node, SuggesterInterface $suggester)
    {
        $this->beConstructedWith($suggester);
        $suggester->handles($node)->willReturn(true);
        $suggester->getSuggestions($node)->willReturn(['foobar']);
        $this->getSuggestions($node)->shouldReturn(['foobar']);
    }
}
