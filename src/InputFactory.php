<?php

declare(strict_types = 1);

namespace hanneskod\comphlete;

use hanneskod\comphlete\LineParser\LineParser;

class InputFactory
{
    /** @var LineParser */
    private $parser;

    public function __construct(LineParser $parser = null)
    {
        $this->parser = $parser ?: new LineParser;
    }

    public function createFromValues(string $line, int $cursorPos, string $toReplace): Input
    {
        return new Input(
            $this->parser->parse($line),
            $cursorPos,
            $toReplace
        );
    }

    public function createFromArgv(array $argv): Input
    {
        $line = $argv[1] ?? '';
        $cursorPos = $argv[2] ?? '0';
        $toReplace = $argv[3] ?? '';

        if (!is_string($line)) {
            throw new \InvalidArgumentException('Input line ($argv[1]) must be a string');
        }

        if (!ctype_digit($cursorPos)) {
            throw new \InvalidArgumentException('Cursor position ($argv[2]) must be a string with digits only');
        }

        if (!is_string($toReplace)) {
            throw new \InvalidArgumentException('Word to replace ($argv[3]) must be a string');
        }

        return $this->createFromValues($line, intval($cursorPos), $toReplace);
    }
}
