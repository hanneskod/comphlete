<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\LineParser;

class Node
{
    /** @var string */
    private $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }

    public function getRawValue(): string
    {
        return $this->value;
    }

    public function getValue(): string
    {
        return (string)preg_replace('/\\\\(.)/', '$1', $this->value);
    }
}
