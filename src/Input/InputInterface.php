<?php

namespace hanneskod\comphlete\Input;

use hanneskod\comphlete\Dictionary;
use hanneskod\comphlete\Word;

interface InputInterface
{
    public function current(): Word;

    public function context(): Dictionary;
}
