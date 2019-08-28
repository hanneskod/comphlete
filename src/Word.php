<?php

declare(strict_types = 1);

namespace hanneskod\comphlete;

final class Word
{
    /** @var string */
    private $word;

    public function __construct(string $word)
    {
        $this->word = $word;
    }

    public function startsWith(Word $word): bool
    {
        $str = (string)$word;

        if (!$str) {
            return true;
        }

        return strpos($this->word, $str) === 0;
    }

    public function equals(string $str): bool
    {
        return $this->word === $str;
    }

    public function __toString(): string
    {
        return $this->word;
    }
}
