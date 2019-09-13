<?php

declare(strict_types = 1);

namespace hanneskod\comphlete;

use hanneskod\comphlete\LineParser\Tree;
use hanneskod\comphlete\LineParser\Node;

class Input
{
    /** @var Tree */
    private $tree;

    /** @var int */
    private $cursorPos;

    /** @var string */
    private $toReplace;

    public function __construct(Tree $tree, int $cursorPos, string $toReplace)
    {
        $this->tree = $tree;
        $this->cursorPos = $cursorPos;
        $this->toReplace = $toReplace;
    }

    /**
     * Get the complete syntax tree representing the input line
     */
    public function getSyntaxTree(): Tree
    {
        return $this->tree;
    }

    /**
     * Get the current syntax node as represented by syntax tree cursor position
     */
    public function getCurrentNode(): Node
    {
        return $this->tree->getNodeAt($this->cursorPos);
    }

    /**
     * Get the current word being replaced, may or may not equal current node
     */
    public function getWordToReplace(): string
    {
        return $this->toReplace;
    }
}
