<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete;

use hanneskod\comphlete\ContextContainerDefinition;
use hanneskod\comphlete\ContextDefinition;
use hanneskod\comphlete\DefinitionInterface;
use hanneskod\comphlete\Suggester\SuggesterInterface;
use hanneskod\comphlete\Suggester\ArgumentSuggester;
use hanneskod\comphlete\LineParser\Argument;
use hanneskod\comphlete\LineParser\Tree;
use PhpSpec\ObjectBehavior;

class ContextContainerDefinitionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(ContextContainerDefinition::class);
    }

    function it_is_a_definition()
    {
        $this->shouldHaveType(DefinitionInterface::class);
    }

    function it_suggests_argument_if_no_context_match(ContextDefinition $context, Tree $tree)
    {
        $context->getContextName()->willReturn('foo');
        $this->addContext($context);
        $tree->getFirstArgument()->willReturn(new Argument(0, 'not-foo'));
        $this->getSuggester($tree)->shouldBeLike(new ArgumentSuggester([0 => ['foo']]));
    }

    function it_suggests_from_context_if_match(ContextDefinition $context, Tree $tree, SuggesterInterface $suggester)
    {
        $context->getContextName()->willReturn('foo');
        $context->getSuggester($tree)->willReturn($suggester);
        $this->addContext($context);
        $tree->getFirstArgument()->willReturn(new Argument(0, 'foo'));
        $this->getSuggester($tree)->shouldReturn($suggester);
    }
}
