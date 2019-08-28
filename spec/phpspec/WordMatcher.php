<?php

declare(strict_types = 1);

namespace spec\hanneskod\comphlete\phpspec;

use hanneskod\comphlete\Word;
use PhpSpec\Matcher\BasicMatcher;
use PhpSpec\Exception\Example\FailureException;

final class WordMatcher extends BasicMatcher
{
    public function supports(string $name, $subject, array $arguments): bool
    {
        return 'returnWord' === $name
            && $subject instanceof Word
            && 1 == \count($arguments)
            && $arguments[0] instanceof Word
        ;
    }

    protected function matches($subject, array $arguments): bool
    {
        if (!$subject instanceof Word) {
            return false;
        }

        $expected = $arguments[0] ?? null;

        if (!$expected instanceof Word) {
            return false;
        }

        return (string)$subject == (string)$expected;
    }

    protected function getFailureException(string $name, $subject, array $arguments): FailureException
    {
        $expected = $arguments[0] ?? null;

        return new FailureException(sprintf(
            "Expected word '%s' to equal '%s', but it does not.",
            $subject instanceof Word ? (string)$subject : '',
            $expected instanceof Word ? (string)$expected : ''
        ));
    }

    protected function getNegativeFailureException(string $name, $subject, array $arguments): FailureException
    {
        $expected = $arguments[0] ?? null;

        return new FailureException(sprintf(
            "Expected wsord '%s' not to equal '%s', but it does.",
            $subject instanceof Word ? (string)$subject : '',
            $expected instanceof Word ? (string)$expected : ''
        ));
    }
}
