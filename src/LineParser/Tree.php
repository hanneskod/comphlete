<?php

declare(strict_types = 1);

namespace hanneskod\comphlete\LineParser;

class Tree
{
    /** @var int */
    private $argumentCount = 0;

    /** @var Node[] */
    private $offsetToNode = [];

    public function __construct(Node ...$nodes)
    {
        foreach ($nodes as $node) {
            if ($node instanceof Argument) {
                $this->argumentCount++;
            }

            foreach (str_split($node->getRawValue()) as $char) {
                $this->offsetToNode[] = $node;
            }
        }
    }

    public function getFirstArgument(): Argument
    {
        foreach ($this->getConcreteNodes() as $node) {
            if ($node instanceof Argument) {
                return $node;
            }
        }

        return new Argument(0, '');
    }

    /**
     * @return Node[] Array of non-space nodes
     */
    public function getConcreteNodes(): array
    {
        $nodes = [];

        foreach ($this->offsetToNode as $node) {
            if ($node instanceof Space) {
                continue;
            }

            if (!in_array($node, $nodes)) {
                $nodes[] = $node;
            }
        }

        return $nodes;
    }

    public function getNodeAt(int $offset): Node
    {
        if ($offset < 0) {
            $offset = 0;
        }

        if ($offset >= count($this->offsetToNode)) {
            return $this->getPreviousOrArgument($offset);
        }

        $node = $this->offsetToNode[$offset] ?? new Space('');

        if ($node instanceof Space) {
            return $this->getPreviousOrArgument($offset);
        }

        return $node;
    }

    private function getPreviousOrArgument(int $offset): Node
    {
        $previous = $this->offsetToNode[$offset - 1] ?? new Space('');

        if ($previous instanceof Space) {
            return new Argument($this->argumentCount, '');
        }

        return $previous;
    }
}
