<?php

namespace hanneskod\comphlete;

use hanneskod\comphlete\Suggester\SuggesterInterface;
use hanneskod\comphlete\LineParser\Tree;

interface DefinitionInterface
{
    public function getSuggester(Tree $tree): SuggesterInterface;
}
