<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\Input;

use hanneskod\comphlete\Input\Input;
use hanneskod\comphlete\Input\InputInterface;
use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class InputSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith(new Dictionary, 0);
        $this->shouldHaveType(Input::class);
        $this->shouldHaveType(InputInterface::class);
    }

    function it_can_get_context()
    {
        $this->beConstructedWith(new Dictionary(new Word('foo'), new Word('bar')), 1);
        $this->context()->shouldReturnDictionary(new Dictionary(new Word('foo')));
    }

    function it_can_get_current_word()
    {
        $this->beConstructedWith(new Dictionary(new Word('foo'), new Word('bar')), 1);
        $this->current()->shouldReturnWord(new Word('bar'));
    }

    function it_can_be_constructed_through_argv()
    {
        $this->beConstructedThrough(function () {
            return Input::fromArgv(['', 'foo bar baz', '2']);
        });
        $this->current()->shouldReturnWord(new Word('baz'));
        $this->context()->shouldReturnDictionary(new Dictionary(new Word('bar')));
    }

    function it_handles_mixed_up_word_count()
    {
        $this->beConstructedThrough(function () {
            return Input::fromArgv(['', 'foo --bar=bar baz', '4']);
        });
        $this->current()->shouldReturnWord(new Word('baz'));
        $this->context()->shouldReturnDictionary(new Dictionary(new Word('--bar=bar')));
    }

    function it_handles_slightly_mixed_up_word_count()
    {
        $this->beConstructedThrough(function () {
            return Input::fromArgv(['', 'foo --bar=bar baz', '3']);
        });
        $this->current()->shouldReturnWord(new Word('--bar=bar'));
        $this->context()->shouldReturnDictionary(new Dictionary(new Word('')));
    }

    function it_keeps_valid_low_count()
    {
        $this->beConstructedThrough(function () {
            return Input::fromArgv(['', 'foo bar --baz=baz', '1']);
        });
        $this->current()->shouldReturnWord(new Word('bar'));
        $this->context()->shouldReturnDictionary(new Dictionary(new Word('')));
    }
}
