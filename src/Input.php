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

    public function __construct(Tree $tree, int $cursorPos)
    {
        $this->tree = $tree;
        $this->cursorPos = $cursorPos;
    }

    public function getSyntaxTree(): Tree
    {
        return $this->tree;
    }

    public function getCurrentNode(): Node
    {
        return $this->tree->getNodeAt($this->cursorPos);
    }
}
