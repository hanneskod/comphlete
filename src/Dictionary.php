<?php

declare(strict_types = 1);

namespace hanneskod\comphlete;

final class Dictionary implements \IteratorAggregate
{
    /** @var Word[] */
    private $words;

    public function __construct(Word... $words)
    {
        $this->words = $words;
    }

    public function at(int $index): Word
    {
        return $this->words[$index] ?? new Word('');
    }

    public function before(int $limit): Dictionary
    {
        $wordsBefore = [];

        foreach ($this->words as $index => $word) {
            if ($index === $limit) {
                break;
            }

            $wordsBefore[] = $word;
        }

        return new Dictionary(...$wordsBefore);
    }

    public function flatten(string $glue): string
    {
        return implode($glue, $this->words);
    }

    public function __toString(): string
    {
        return $this->flatten(' ');
    }

    public function getIterator(): iterable
    {
        return new \ArrayIterator($this->words);
    }

    public function is(Word $word): bool
    {
        return $this->flatten('') == (string)$word;
    }

    /**
     * @return Word[]]
     */
    public function getWords(): array
    {
        return $this->words;
    }
}
