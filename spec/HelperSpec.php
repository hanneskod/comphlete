<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete;

use hanneskod\comphlete\Helper;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class HelperSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(Helper::class);
    }

    function it_can_flatten_multidimensional_array()
    {
        $this->flatten(['foo', ['bar', ['baz']]])->shouldReturn(['foo', 'bar', 'baz']);
    }

    function it_removes_falsy_values_while_flatten()
    {
        $this->flatten([null])->shouldReturn([]);
    }

    function it_can_filter_on_starts_with()
    {
        $this->filter(['foo', 'bar', 'baz'], 'b')->shouldReturn(['bar', 'baz']);
    }

    function it_can_filter_with_empty_needle()
    {
        $this->filter(['foo', 'bar', 'baz'], '')->shouldReturn(['foo', 'bar', 'baz']);
    }

    function it_can_append_spaces()
    {
        $this->addTrailingSpaces(['foo', 'bar', 'baz'], '')->shouldReturn(['foo ', 'bar ', 'baz ']);
    }

    function it_can_dump_suggestions()
    {
        $this->dump(['foo', 'bar'], '&')->shouldReturn('foo&bar');
    }

    function it_can_explode()
    {
        $this->explode(['foo:bar', 'foo:baz'])->shouldReturn(['foo:bar', 'foo', 'bar', 'foo:baz', 'baz']);
    }

    function it_can_explode_only_alphanum()
    {
        $this->explode(['foobar'])->shouldReturn(['foobar']);
    }

    function it_ignores_spaces_on_explode()
    {
        $this->explode(['foo:bar '])->shouldReturn(['foo:bar ', 'foo', 'bar ']);
    }
}
