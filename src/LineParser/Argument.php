<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\LineParser;

class Argument extends Node
{
    /** @var int */
    private $argumentNr;

    public function __construct(int $argumentNr, string $value)
    {
        parent::__construct($value);
        $this->argumentNr = $argumentNr;
    }

    public function getArgumentNr(): int
    {
        return $this->argumentNr;
    }
}
