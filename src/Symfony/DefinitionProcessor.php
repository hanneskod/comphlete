<?php

namespace hanneskod\comphlete\Symfony;

interface DefinitionProcessor
{
    public function processDefinition(SymfonyConsoleDefinition $definition): SymfonyConsoleDefinition;
}
