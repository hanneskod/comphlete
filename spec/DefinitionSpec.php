<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete;

use hanneskod\comphlete\Definition;
use hanneskod\comphlete\DefinitionInterface;
use hanneskod\comphlete\Suggester\CombinedSuggester;
use hanneskod\comphlete\Suggester\ArgumentSuggester;
use hanneskod\comphlete\Suggester\OptionSuggester;
use hanneskod\comphlete\Suggester\OptionValueSuggester;
use hanneskod\comphlete\LineParser\Tree;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DefinitionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Definition::class);
    }

    function it_is_a_definition()
    {
        $this->shouldHaveType(DefinitionInterface::class);
    }

    function it_definies_arguments(Tree $tree)
    {
        $this->addArgument(0, ['foo']);
        $this->getSuggester($tree)->shouldBeLike(
            new CombinedSuggester(
                new ArgumentSuggester([0 => ['foo']]),
                new OptionSuggester([]),
                new OptionValueSuggester([])
            )
        );
    }

    function it_defines_options(Tree $tree)
    {
        $this->addOption('foo', ['bar']);
        $this->getSuggester($tree)->shouldBeLike(
            new CombinedSuggester(
                new ArgumentSuggester([]),
                new OptionSuggester(['--foo=']),
                new OptionValueSuggester(['--foo' => ['bar']])
            )
        );
    }

    function it_defines_options_with_no_suggestions(Tree $tree)
    {
        $this->addOption('foo');
        $this->getSuggester($tree)->shouldBeLike(
            new CombinedSuggester(
                new ArgumentSuggester([]),
                new OptionSuggester(['--foo ']),
                new OptionValueSuggester(['--foo' => []])
            )
        );
    }

    function it_can_override_options_with_no_suggestions(Tree $tree)
    {
        $this->addOption('foo', ['bar']);
        $this->addOption('foo');
        $this->getSuggester($tree)->shouldBeLike(
            new CombinedSuggester(
                new ArgumentSuggester([]),
                new OptionSuggester(['--foo ']),
                new OptionValueSuggester(['--foo' => []])
            )
        );
    }
}
