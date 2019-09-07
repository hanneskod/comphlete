<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\LineParser;

class OptionValue extends Node
{
    /** @var string */
    private $option;

    public function __construct(string $option, string $value)
    {
        parent::__construct($value);
        $this->option = $option;
    }

    public function getOption(): string
    {
        return $this->option;
    }
}
