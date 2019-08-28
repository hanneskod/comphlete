<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\phpspec;

use hanneskod\comphlete\Dictionary;
use PhpSpec\Matcher\BasicMatcher;
use PhpSpec\Exception\Example\FailureException;

final class DictionaryMatcher extends BasicMatcher
{
    public function supports(string $name, $subject, array $arguments): bool
    {
        return 'returnDictionary' === $name
            && $subject instanceof Dictionary
            && 1 == \count($arguments)
            && $arguments[0] instanceof Dictionary
        ;
    }

    protected function matches($subject, array $arguments): bool
    {
        if (!$subject instanceof Dictionary) {
            return false;
        }

        $expected = $arguments[0] ?? null;

        if (!$expected instanceof Dictionary) {
            return false;
        }

        return $subject->flatten('') == $expected->flatten('');
    }

    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        $expected = $arguments[0] ?? null;

        return new FailureException(sprintf(
            "Expected dictionary '%s' to equal '%s', but it does not.",
            $subject instanceof Dictionary ? $subject->flatten(', ') : '',
            $expected instanceof Dictionary ? $expected->flatten(', ') : ''
        ));
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        $expected = $arguments[0] ?? null;

        return new FailureException(sprintf(
            "Expected dictionary '%s' not to equal '%s', but it does.",
            $subject instanceof Dictionary ? $subject->flatten(', ') : '',
            $expected instanceof Dictionary ? $expected->flatten(', ') : ''
        ));
    }
}
