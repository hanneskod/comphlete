<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete;

use hanneskod\comphlete\Completer;
use hanneskod\comphlete\DefinitionInterface;
use hanneskod\comphlete\Input;
use hanneskod\comphlete\Suggester\SuggesterInterface;
use hanneskod\comphlete\LineParser\Tree;
use hanneskod\comphlete\LineParser\Node;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CompleterSpec extends ObjectBehavior
{
    function let(DefinitionInterface $definition)
    {
        $this->beConstructedWith($definition);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(Completer::class);
    }

    function it_completes($definition, SuggesterInterface $suggester, Input $input, Tree $tree, Node $node)
    {
        $input->getSyntaxTree()->willReturn($tree);
        $input->getCurrentNode()->willReturn($node);

        $definition->getSuggester($tree)->willReturn($suggester);

        $suggester->handles($node)->willReturn(true);
        $suggester->getSuggestions($node)->willReturn(['foobar']);

        $this->complete($input)->shouldReturn(['foobar']);
    }

    function it_does_not_complete_if_suggester_does_not_handle(
        $definition,
        SuggesterInterface $suggester,
        Input $input,
        Tree $tree,
        Node $node
    ) {
        $input->getSyntaxTree()->willReturn($tree);
        $input->getCurrentNode()->willReturn($node);

        $definition->getSuggester($tree)->willReturn($suggester);

        $suggester->handles($node)->willReturn(false);

        $this->complete($input)->shouldReturn([]);
    }
}
