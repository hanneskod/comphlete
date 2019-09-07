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

    public function createFromValues(string $line, int $cursorPos): Input
    {
        return new Input(
            $this->parser->parse($line),
            $cursorPos
        );
    }

    public function createFromArgv(array $argv): Input
    {
        $line = $argv[1] ?? '';
        $cursorPos = $argv[2] ?? '0';

        if (!is_string($line)) {
            throw new \InvalidArgumentException('Input line ($argv[1]) must be a string');
        }

        if (!ctype_digit($cursorPos)) {
            throw new \InvalidArgumentException('Cursor position ($argv[2]) must be a string with digits only');
        }

        return $this->createFromValues($line, intval($cursorPos));
    }
}
