<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\Input;

use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;

final class Input implements InputInterface
{
    /** @var Dictionary */
    private $dictionary;

    /** @var int */
    private $cursorPos;

    public function __construct(Dictionary $dictionary, int $cursorPos)
    {
        $this->dictionary = $dictionary;
        $this->cursorPos = $cursorPos;
    }

    public static function fromArgv(array $argv): self
    {
        $words = explode(' ', $argv[1] ?? '');

        array_shift($words);

        $cursorPos = intval($argv[2] ?? 1)-1;

        foreach ($words as $index => $word) {
            if (preg_match('/=(.*)$/', $word, $matches)) {
                if ($cursorPos > $index) {
                    --$cursorPos;
                }
                if ($matches[1] && $cursorPos > $index) {
                    --$cursorPos;
                }
            }
        }

        $words = array_map(
            function ($word) {
                return new Word($word);
            },
            $words
        );

        return new self(new Dictionary(...$words), $cursorPos);
    }

    public function current(): Word
    {
        return $this->dictionary->at($this->cursorPos);
    }

    public function context(): Dictionary
    {
        return $this->dictionary->before($this->cursorPos);
    }
}
