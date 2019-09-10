<?php

declare(strict_types = 1);

/**
 * Wrapps the result of a shell execution
 */
class Result
{
    /**
     * @var int
     */
    private $returnCode;

    /**
     * @var string
     */
    private $output;

    /**
     * @var string
     */
    private $errorOutput;

    public function __construct(int $returnCode, string $output, string $errorOutput)
    {
        $this->returnCode = $returnCode;
        $this->output = $output;
        $this->errorOutput = $errorOutput;
    }

    public function getReturnCode(): int
    {
        return $this->returnCode;
    }

    public function isError(): bool
    {
        return 0 != $this->getReturnCode();
    }

    public function getOutput(): string
    {
        return $this->output;
    }

    public function getErrorOutput(): string
    {
        return $this->errorOutput;
    }
}
