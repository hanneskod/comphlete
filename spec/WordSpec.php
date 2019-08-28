<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete;

use hanneskod\comphlete\Word;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WordSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith('');
        $this->shouldHaveType(Word::class);
    }

    function it_can_start_with()
    {
        $this->beConstructedWith('foo');
        $this->startsWith(new Word('f'))->shouldReturn(true);
        $this->startsWith(new Word('foo'))->shouldReturn(true);
    }

    function it_can_find_not_starting_with()
    {
        $this->beConstructedWith('foo');
        $this->startsWith(new Word('bar'))->shouldReturn(false);
    }

    function it_can_find_starting_with_empty_string()
    {
        $this->beConstructedWith('foo');
        $this->startsWith(new Word(''))->shouldReturn(true);
    }

    function it_can_find_complete_match()
    {
        $this->beConstructedWith('foo');
        $this->equals('foo')->shouldReturn(true);
    }

    function it_can_find_complete_missmatch()
    {
        $this->beConstructedWith('foo');
        $this->equals('fo')->shouldReturn(false);
    }

    function it_can_cast_to_string()
    {
        $this->beConstructedWith('foo');
        $this->__toString()->shouldReturn('foo');
    }
}
