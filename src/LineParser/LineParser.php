<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\LineParser;

class LineParser
{
    /** @var Grammar */
    private $grammar;

    public function __construct()
    {
        $this->grammar = new Grammar;
    }

    public function parse(string $line): Tree
    {
        return $this->grammar->parse($line);
    }
}
