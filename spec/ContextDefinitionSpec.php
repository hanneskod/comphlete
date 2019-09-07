<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete;

use hanneskod\comphlete\ContextDefinition;
use hanneskod\comphlete\DefinitionInterface;
use hanneskod\comphlete\Suggester\CombinedSuggester;
use hanneskod\comphlete\Suggester\ArgumentSuggester;
use hanneskod\comphlete\Suggester\OptionSuggester;
use hanneskod\comphlete\Suggester\OptionValueSuggester;
use hanneskod\comphlete\LineParser\Tree;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ContextDefinitionSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith('');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(ContextDefinition::class);
    }

    function it_is_a_definition()
    {
        $this->shouldHaveType(DefinitionInterface::class);
    }

    function it_can_get_context_name()
    {
        $this->beConstructedWith('foobar');
        $this->getContextName()->shouldReturn('foobar');
    }

    function it_definies_context_as_argument(Tree $tree)
    {
        $this->beConstructedWith('foo');
        $this->getSuggester($tree)->shouldBeLike(
            new CombinedSuggester(
                new ArgumentSuggester([0 => ['foo']]),
                new OptionSuggester([]),
                new OptionValueSuggester([])
            )
        );
    }

    function it_throws_on_redefinition_of_context_argument(Tree $tree)
    {
        $this->beConstructedWith('');
        $this->shouldThrow(\LogicException::class)->duringAddArgument(0, []);
    }

    function it_definies_other_arguments(Tree $tree)
    {
        $this->beConstructedWith('foo');
        $this->addArgument(1, ['bar']);
        $this->getSuggester($tree)->shouldBeLike(
            new CombinedSuggester(
                new ArgumentSuggester([0 => ['foo'], 1 => ['bar']]),
                new OptionSuggester([]),
                new OptionValueSuggester([])
            )
        );
    }

    function it_defines_options(Tree $tree)
    {
        $this->beConstructedWith('');
        $this->addOption('foo', ['bar']);
        $this->getSuggester($tree)->shouldBeLike(
            new CombinedSuggester(
                new ArgumentSuggester([0 => ['']]),
                new OptionSuggester(['--foo=']),
                new OptionValueSuggester(['--foo' => ['bar']])
            )
        );
    }
}
