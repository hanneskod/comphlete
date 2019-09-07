<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete;

use hanneskod\comphlete\InputFactory;
use hanneskod\comphlete\Input;
use hanneskod\comphlete\LineParser\LineParser;
use hanneskod\comphlete\LineParser\Tree;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InputFactorySpec extends ObjectBehavior
{
    function let(LineParser $parser)
    {
        $this->beConstructedWith($parser);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(InputFactory::class);
    }

    function it_can_create_from_values($parser, Tree $tree)
    {
        $parser->parse('foobar')->willReturn($tree);
        $this->createFromValues('foobar', 1)->shouldBeLike(
            new Input($tree->getWrappedObject(), 1)
        );
    }

    function it_can_create_from_argv($parser, Tree $tree)
    {
        $parser->parse('foobar')->willReturn($tree);
        $this->createFromArgv(['', 'foobar', '1'])->shouldBeLike(
            new Input($tree->getWrappedObject(), 1)
        );
    }

    function it_handles_argv_defaults($parser, Tree $tree)
    {
        $parser->parse('')->willReturn($tree);
        $this->createFromArgv([])->shouldBeLike(
            new Input($tree->getWrappedObject(), 0)
        );
    }

    function it_throws_on_argv_non_string_line()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->duringCreateFromArgv(['', ['an-array-not-a-string']]);
    }

    function it_throws_on_argv_non_digit_cursor_pos()
    {
        $this->shouldThrow(\InvalidArgumentException::class)->duringCreateFromArgv(['', '', 'not-digits']);
    }
}
