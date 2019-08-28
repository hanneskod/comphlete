<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete;

use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DictionarySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Dictionary::class);
    }

    function it_can_index_words()
    {
        $this->beConstructedWith(new Word('foo'), new Word('bar'));
        $this->at(1)->shouldReturnWord(new Word('bar'));
    }

    function it_defaults_to_empty_word()
    {
        $this->at(0)->shouldBeLike(new Word(''));
    }

    function it_is_iterable()
    {
        $this->shouldHaveType(\IteratorAggregate::class);
    }

    function it_can_iterate()
    {
        $foo = new Word('foo');
        $bar = new Word('bar');
        $this->beConstructedWith($foo, $bar);
        $this->getIterator()->shouldIterateAs([$foo, $bar]);
    }

    function it_can_flatten()
    {
        $this->beConstructedWith(new Word('foo'), new Word('bar'));
        $this->flatten('+')->shouldReturn('foo+bar');
    }

    function it_can_fins_words_before_index()
    {
        $this->beConstructedWith(new Word('foo'), new Word('bar'));
        $this->before(1)->shouldReturnDictionary(new Dictionary(new Word('foo')));
    }

    function it_finds_all_words_on_large_index()
    {
        $this->beConstructedWith(new Word('foo'));
        $this->before(10)->shouldReturnDictionary(new Dictionary(new Word('foo')));
    }

    function it_equals_word()
    {
        $this->beConstructedWith(new Word('foo'));
        $this->is(new Word('foo'))->shouldReturn(true);
    }

    function it_can_get_words()
    {
        $word = new Word('foo');
        $this->beConstructedWith($word);
        $this->getWords()->shouldReturn([$word]);
    }

    function it_fails_to_equals_word()
    {
        $this->beConstructedWith(new Word('foo'));
        $this->is(new Word('bar'))->shouldReturn(false);
    }

    function it_can_cast_to_string()
    {
        $this->beConstructedWith(new Word('foo'), new Word('bar'));
        $this->__toString()->shouldReturn('foo bar');
    }
}
