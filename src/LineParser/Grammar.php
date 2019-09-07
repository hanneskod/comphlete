<?php

namespace hanneskod\comphlete\LineParser;

use hanneskod\comphlete\Helper;

class Grammar
{
    protected $string;
    protected $position;
    protected $value;
    protected $cache;
    protected $cut;
    protected $errors;
    protected $warnings;

    protected function parseTREE()
    {
        $_position = $this->position;

        if (isset($this->cache['TREE'][$_position])) {
            $_success = $this->cache['TREE'][$_position]['success'];
            $this->position = $this->cache['TREE'][$_position]['position'];
            $this->value = $this->cache['TREE'][$_position]['value'];

            return $_success;
        }

        $_value1 = array();

        $_success = $this->parseRESET_ARG_COUNT();

        if ($_success) {
            $_value1[] = $this->value;

            $_success = $this->parseLINE();

            if ($_success) {
                $nodes = $this->value;
            }
        }

        if ($_success) {
            $_value1[] = $this->value;

            $this->value = $_value1;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$nodes) {
                return new Tree(...Helper::flatten($nodes));
            });
        }

        $this->cache['TREE'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'TREE');
        }

        return $_success;
    }

    protected function parseLINE()
    {
        $_position = $this->position;

        if (isset($this->cache['LINE'][$_position])) {
            $_success = $this->cache['LINE'][$_position]['success'];
            $this->position = $this->cache['LINE'][$_position]['position'];
            $this->value = $this->cache['LINE'][$_position]['value'];

            return $_success;
        }

        $_value21 = array();

        $_position2 = $this->position;
        $_cut3 = $this->cut;

        $this->cut = false;
        $_success = $this->parseSPACE();

        if (!$_success && !$this->cut) {
            $_success = true;
            $this->position = $_position2;
            $this->value = null;
        }

        $this->cut = $_cut3;

        if ($_success) {
            $_value21[] = $this->value;

            $_position4 = $this->position;
            $_cut5 = $this->cut;

            $this->cut = false;
            $_success = $this->parseCOMMAND();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position4;
                $this->value = null;
            }

            $this->cut = $_cut5;
        }

        if ($_success) {
            $_value21[] = $this->value;

            $_value12 = array();
            $_cut13 = $this->cut;

            while (true) {
                $_position11 = $this->position;

                $this->cut = false;
                $_value10 = array();

                $_position6 = $this->position;
                $_cut7 = $this->cut;

                $this->cut = false;
                $_success = $this->parseEND_OF_OPTIONS();

                if (!$_success) {
                    $_success = true;
                    $this->value = null;
                } else {
                    $_success = false;
                }

                $this->position = $_position6;
                $this->cut = $_cut7;

                if ($_success) {
                    $_value10[] = $this->value;

                    $_position8 = $this->position;
                    $_cut9 = $this->cut;

                    $this->cut = false;
                    $_success = $this->parseOPTION();

                    if (!$_success && !$this->cut) {
                        $this->position = $_position8;

                        $_success = $this->parseARGUMENT();
                    }

                    if (!$_success && !$this->cut) {
                        $this->position = $_position8;

                        $_success = $this->parseSPACE();
                    }

                    $this->cut = $_cut9;
                }

                if ($_success) {
                    $_value10[] = $this->value;

                    $this->value = $_value10;
                }

                if (!$_success) {
                    break;
                }

                $_value12[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position11;
                $this->value = $_value12;
            }

            $this->cut = $_cut13;
        }

        if ($_success) {
            $_value21[] = $this->value;

            $_position14 = $this->position;
            $_cut15 = $this->cut;

            $this->cut = false;
            $_success = $this->parseEND_OF_OPTIONS();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position14;
                $this->value = null;
            }

            $this->cut = $_cut15;
        }

        if ($_success) {
            $_value21[] = $this->value;

            $_value19 = array();
            $_cut20 = $this->cut;

            while (true) {
                $_position18 = $this->position;

                $this->cut = false;
                $_position16 = $this->position;
                $_cut17 = $this->cut;

                $this->cut = false;
                $_success = $this->parseARGUMENT();

                if (!$_success && !$this->cut) {
                    $this->position = $_position16;

                    $_success = $this->parseSPACE();
                }

                $this->cut = $_cut17;

                if (!$_success) {
                    break;
                }

                $_value19[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position18;
                $this->value = $_value19;
            }

            $this->cut = $_cut20;
        }

        if ($_success) {
            $_value21[] = $this->value;

            $this->value = $_value21;
        }

        $this->cache['LINE'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'LINE');
        }

        return $_success;
    }

    protected function parseRESET_ARG_COUNT()
    {
        $_position = $this->position;

        if (isset($this->cache['RESET_ARG_COUNT'][$_position])) {
            $_success = $this->cache['RESET_ARG_COUNT'][$_position]['success'];
            $this->position = $this->cache['RESET_ARG_COUNT'][$_position]['position'];
            $this->value = $this->cache['RESET_ARG_COUNT'][$_position]['value'];

            return $_success;
        }

        if (substr($this->string, $this->position, strlen('')) === '') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen(''));
            $this->position += strlen('');
        } else {
            $_success = false;

            $this->report($this->position, '\'\'');
        }

        if ($_success) {
            $this->value = call_user_func(function () {
                $this->argNr = 0;
            });
        }

        $this->cache['RESET_ARG_COUNT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'RESET_ARG_COUNT');
        }

        return $_success;
    }

    protected function parseCOMMAND()
    {
        $_position = $this->position;

        if (isset($this->cache['COMMAND'][$_position])) {
            $_success = $this->cache['COMMAND'][$_position]['success'];
            $this->position = $this->cache['COMMAND'][$_position]['position'];
            $this->value = $this->cache['COMMAND'][$_position]['value'];

            return $_success;
        }

        $_position25 = $this->position;

        $_success = $this->parseCHAR();

        if ($_success) {
            $_value23 = array($this->value);
            $_cut24 = $this->cut;

            while (true) {
                $_position22 = $this->position;

                $this->cut = false;
                $_success = $this->parseCHAR();

                if (!$_success) {
                    break;
                }

                $_value23[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position22;
                $this->value = $_value23;
            }

            $this->cut = $_cut24;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position25, $this->position - $_position25));
        }

        if ($_success) {
            $name = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$name) {
                return new Node($name);
            });
        }

        $this->cache['COMMAND'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'COMMAND');
        }

        return $_success;
    }

    protected function parseARGUMENT()
    {
        $_position = $this->position;

        if (isset($this->cache['ARGUMENT'][$_position])) {
            $_success = $this->cache['ARGUMENT'][$_position]['success'];
            $this->position = $this->cache['ARGUMENT'][$_position]['position'];
            $this->value = $this->cache['ARGUMENT'][$_position]['value'];

            return $_success;
        }

        $_value30 = array();

        $_position29 = $this->position;

        $_success = $this->parseCHAR();

        if ($_success) {
            $_value27 = array($this->value);
            $_cut28 = $this->cut;

            while (true) {
                $_position26 = $this->position;

                $this->cut = false;
                $_success = $this->parseCHAR();

                if (!$_success) {
                    break;
                }

                $_value27[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position26;
                $this->value = $_value27;
            }

            $this->cut = $_cut28;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position29, $this->position - $_position29));
        }

        if ($_success) {
            $name = $this->value;
        }

        if ($_success) {
            $_value30[] = $this->value;

            $_success = true;
            $this->value = null;

            $this->cut = true;
        }

        if ($_success) {
            $_value30[] = $this->value;

            $this->value = $_value30;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$name) {
                return new Argument($this->argNr++, $name);
            });
        }

        $this->cache['ARGUMENT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ARGUMENT');
        }

        return $_success;
    }

    protected function parseOPTION()
    {
        $_position = $this->position;

        if (isset($this->cache['OPTION'][$_position])) {
            $_success = $this->cache['OPTION'][$_position]['success'];
            $this->position = $this->cache['OPTION'][$_position]['position'];
            $this->value = $this->cache['OPTION'][$_position]['value'];

            return $_success;
        }

        $_position31 = $this->position;
        $_cut32 = $this->cut;

        $this->cut = false;
        $_success = $this->parseLONG_OPTION_WITH_VALUE();

        if (!$_success && !$this->cut) {
            $this->position = $_position31;

            $_success = $this->parseLONG_OPTION();
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position31;

            $_success = $this->parseSHORT_OPTION();
        }

        $this->cut = $_cut32;

        $this->cache['OPTION'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OPTION');
        }

        return $_success;
    }

    protected function parseSHORT_OPTION()
    {
        $_position = $this->position;

        if (isset($this->cache['SHORT_OPTION'][$_position])) {
            $_success = $this->cache['SHORT_OPTION'][$_position]['success'];
            $this->position = $this->cache['SHORT_OPTION'][$_position]['position'];
            $this->value = $this->cache['SHORT_OPTION'][$_position]['value'];

            return $_success;
        }

        $_position40 = $this->position;

        $_value39 = array();

        if (substr($this->string, $this->position, strlen('-')) === '-') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('-'));
            $this->position += strlen('-');
        } else {
            $_success = false;

            $this->report($this->position, '\'-\'');
        }

        if ($_success) {
            $_value39[] = $this->value;

            $_value37 = array();
            $_cut38 = $this->cut;

            while (true) {
                $_position36 = $this->position;

                $this->cut = false;
                $_value35 = array();

                $_position33 = $this->position;
                $_cut34 = $this->cut;

                $this->cut = false;
                if (substr($this->string, $this->position, strlen('-')) === '-') {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen('-'));
                    $this->position += strlen('-');
                } else {
                    $_success = false;

                    $this->report($this->position, '\'-\'');
                }

                if (!$_success) {
                    $_success = true;
                    $this->value = null;
                } else {
                    $_success = false;
                }

                $this->position = $_position33;
                $this->cut = $_cut34;

                if ($_success) {
                    $_value35[] = $this->value;

                    $_success = $this->parseCHAR();
                }

                if ($_success) {
                    $_value35[] = $this->value;

                    $this->value = $_value35;
                }

                if (!$_success) {
                    break;
                }

                $_value37[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position36;
                $this->value = $_value37;
            }

            $this->cut = $_cut38;
        }

        if ($_success) {
            $_value39[] = $this->value;

            $this->value = $_value39;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position40, $this->position - $_position40));
        }

        if ($_success) {
            $option = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$option) {
                return new Option($option);
            });
        }

        $this->cache['SHORT_OPTION'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'SHORT_OPTION');
        }

        return $_success;
    }

    protected function parseLONG_OPTION()
    {
        $_position = $this->position;

        if (isset($this->cache['LONG_OPTION'][$_position])) {
            $_success = $this->cache['LONG_OPTION'][$_position]['success'];
            $this->position = $this->cache['LONG_OPTION'][$_position]['position'];
            $this->value = $this->cache['LONG_OPTION'][$_position]['value'];

            return $_success;
        }

        $_position48 = $this->position;

        $_value47 = array();

        if (substr($this->string, $this->position, strlen('--')) === '--') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('--'));
            $this->position += strlen('--');
        } else {
            $_success = false;

            $this->report($this->position, '\'--\'');
        }

        if ($_success) {
            $_value47[] = $this->value;

            $_value43 = array();

            $_position41 = $this->position;
            $_cut42 = $this->cut;

            $this->cut = false;
            $_success = $this->parseOPTION_ASSIGNMENT();

            if (!$_success) {
                $_success = true;
                $this->value = null;
            } else {
                $_success = false;
            }

            $this->position = $_position41;
            $this->cut = $_cut42;

            if ($_success) {
                $_value43[] = $this->value;

                $_success = $this->parseCHAR();
            }

            if ($_success) {
                $_value43[] = $this->value;

                $this->value = $_value43;
            }

            if ($_success) {
                $_value45 = array($this->value);
                $_cut46 = $this->cut;

                while (true) {
                    $_position44 = $this->position;

                    $this->cut = false;
                    $_value43 = array();

                    $_position41 = $this->position;
                    $_cut42 = $this->cut;

                    $this->cut = false;
                    $_success = $this->parseOPTION_ASSIGNMENT();

                    if (!$_success) {
                        $_success = true;
                        $this->value = null;
                    } else {
                        $_success = false;
                    }

                    $this->position = $_position41;
                    $this->cut = $_cut42;

                    if ($_success) {
                        $_value43[] = $this->value;

                        $_success = $this->parseCHAR();
                    }

                    if ($_success) {
                        $_value43[] = $this->value;

                        $this->value = $_value43;
                    }

                    if (!$_success) {
                        break;
                    }

                    $_value45[] = $this->value;
                }

                if (!$this->cut) {
                    $_success = true;
                    $this->position = $_position44;
                    $this->value = $_value45;
                }

                $this->cut = $_cut46;
            }
        }

        if ($_success) {
            $_value47[] = $this->value;

            $this->value = $_value47;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position48, $this->position - $_position48));
        }

        if ($_success) {
            $name = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$name) {
                return new Option($name);
            });
        }

        $this->cache['LONG_OPTION'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'LONG_OPTION');
        }

        return $_success;
    }

    protected function parseLONG_OPTION_WITH_VALUE()
    {
        $_position = $this->position;

        if (isset($this->cache['LONG_OPTION_WITH_VALUE'][$_position])) {
            $_success = $this->cache['LONG_OPTION_WITH_VALUE'][$_position]['success'];
            $this->position = $this->cache['LONG_OPTION_WITH_VALUE'][$_position]['position'];
            $this->value = $this->cache['LONG_OPTION_WITH_VALUE'][$_position]['value'];

            return $_success;
        }

        $_value57 = array();

        $_success = $this->parseLONG_OPTION();

        if ($_success) {
            $option = $this->value;
        }

        if ($_success) {
            $_value57[] = $this->value;

            $_position49 = $this->position;
            $_cut50 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSPACE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position49;
                $this->value = null;
            }

            $this->cut = $_cut50;

            if ($_success) {
                $s1 = $this->value;
            }
        }

        if ($_success) {
            $_value57[] = $this->value;

            $_success = $this->parseOPTION_ASSIGNMENT();

            if ($_success) {
                $assignment = $this->value;
            }
        }

        if ($_success) {
            $_value57[] = $this->value;

            $_position51 = $this->position;
            $_cut52 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSPACE();

            if (!$_success && !$this->cut) {
                $_success = true;
                $this->position = $_position51;
                $this->value = null;
            }

            $this->cut = $_cut52;

            if ($_success) {
                $s2 = $this->value;
            }
        }

        if ($_success) {
            $_value57[] = $this->value;

            $_position56 = $this->position;

            $_value54 = array();
            $_cut55 = $this->cut;

            while (true) {
                $_position53 = $this->position;

                $this->cut = false;
                $_success = $this->parseCHAR();

                if (!$_success) {
                    break;
                }

                $_value54[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position53;
                $this->value = $_value54;
            }

            $this->cut = $_cut55;

            if ($_success) {
                $this->value = strval(substr($this->string, $_position56, $this->position - $_position56));
            }

            if ($_success) {
                $value = $this->value;
            }
        }

        if ($_success) {
            $_value57[] = $this->value;

            $this->value = $_value57;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$option, &$s1, &$assignment, &$s2, &$value) {
                return [$option, $s1, $assignment, $s2, new OptionValue($option->getValue(), $value)];
            });
        }

        $this->cache['LONG_OPTION_WITH_VALUE'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'LONG_OPTION_WITH_VALUE');
        }

        return $_success;
    }

    protected function parseOPTION_ASSIGNMENT()
    {
        $_position = $this->position;

        if (isset($this->cache['OPTION_ASSIGNMENT'][$_position])) {
            $_success = $this->cache['OPTION_ASSIGNMENT'][$_position]['success'];
            $this->position = $this->cache['OPTION_ASSIGNMENT'][$_position]['position'];
            $this->value = $this->cache['OPTION_ASSIGNMENT'][$_position]['value'];

            return $_success;
        }

        if (substr($this->string, $this->position, strlen('=')) === '=') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('='));
            $this->position += strlen('=');
        } else {
            $_success = false;

            $this->report($this->position, '\'=\'');
        }

        if ($_success) {
            $this->value = call_user_func(function () {
                return new OptionAssignmentOperator('=');
            });
        }

        $this->cache['OPTION_ASSIGNMENT'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'OPTION_ASSIGNMENT');
        }

        return $_success;
    }

    protected function parseEND_OF_OPTIONS()
    {
        $_position = $this->position;

        if (isset($this->cache['END_OF_OPTIONS'][$_position])) {
            $_success = $this->cache['END_OF_OPTIONS'][$_position]['success'];
            $this->position = $this->cache['END_OF_OPTIONS'][$_position]['position'];
            $this->value = $this->cache['END_OF_OPTIONS'][$_position]['value'];

            return $_success;
        }

        $_value58 = array();

        if (substr($this->string, $this->position, strlen('--')) === '--') {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen('--'));
            $this->position += strlen('--');
        } else {
            $_success = false;

            $this->report($this->position, '\'--\'');
        }

        if ($_success) {
            $_value58[] = $this->value;

            $_success = $this->parseSPACE();

            if ($_success) {
                $space = $this->value;
            }
        }

        if ($_success) {
            $_value58[] = $this->value;

            $this->value = $_value58;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$space) {
                return [new EndOfOptions('--'), $space];
            });
        }

        $this->cache['END_OF_OPTIONS'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'END_OF_OPTIONS');
        }

        return $_success;
    }

    protected function parseCHAR()
    {
        $_position = $this->position;

        if (isset($this->cache['CHAR'][$_position])) {
            $_success = $this->cache['CHAR'][$_position]['success'];
            $this->position = $this->cache['CHAR'][$_position]['position'];
            $this->value = $this->cache['CHAR'][$_position]['value'];

            return $_success;
        }

        $_position64 = $this->position;

        $_position62 = $this->position;
        $_cut63 = $this->cut;

        $this->cut = false;
        $_success = $this->parseESCAPE();

        if (!$_success && !$this->cut) {
            $this->position = $_position62;

            $_value61 = array();

            $_position59 = $this->position;
            $_cut60 = $this->cut;

            $this->cut = false;
            $_success = $this->parseSPACE();

            if (!$_success) {
                $_success = true;
                $this->value = null;
            } else {
                $_success = false;
            }

            $this->position = $_position59;
            $this->cut = $_cut60;

            if ($_success) {
                $_value61[] = $this->value;

                if ($this->position < strlen($this->string)) {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, 1);
                    $this->position += 1;
                } else {
                    $_success = false;
                }
            }

            if ($_success) {
                $_value61[] = $this->value;

                $this->value = $_value61;
            }
        }

        $this->cut = $_cut63;

        if ($_success) {
            $this->value = strval(substr($this->string, $_position64, $this->position - $_position64));
        }

        $this->cache['CHAR'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'CHAR');
        }

        return $_success;
    }

    protected function parseESCAPE()
    {
        $_position = $this->position;

        if (isset($this->cache['ESCAPE'][$_position])) {
            $_success = $this->cache['ESCAPE'][$_position]['success'];
            $this->position = $this->cache['ESCAPE'][$_position]['position'];
            $this->value = $this->cache['ESCAPE'][$_position]['value'];

            return $_success;
        }

        $_position66 = $this->position;

        $_value65 = array();

        if (substr($this->string, $this->position, strlen("\\")) === "\\") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen("\\"));
            $this->position += strlen("\\");
        } else {
            $_success = false;

            $this->report($this->position, '"\\\\"');
        }

        if ($_success) {
            $_value65[] = $this->value;

            if ($this->position < strlen($this->string)) {
                $_success = true;
                $this->value = substr($this->string, $this->position, 1);
                $this->position += 1;
            } else {
                $_success = false;
            }
        }

        if ($_success) {
            $_value65[] = $this->value;

            $this->value = $_value65;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position66, $this->position - $_position66));
        }

        $this->cache['ESCAPE'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'ESCAPE');
        }

        return $_success;
    }

    protected function parseSPACE()
    {
        $_position = $this->position;

        if (isset($this->cache['SPACE'][$_position])) {
            $_success = $this->cache['SPACE'][$_position]['success'];
            $this->position = $this->cache['SPACE'][$_position]['position'];
            $this->value = $this->cache['SPACE'][$_position]['value'];

            return $_success;
        }

        $_position72 = $this->position;

        $_position67 = $this->position;
        $_cut68 = $this->cut;

        $this->cut = false;
        if (substr($this->string, $this->position, strlen(" ")) === " ") {
            $_success = true;
            $this->value = substr($this->string, $this->position, strlen(" "));
            $this->position += strlen(" ");
        } else {
            $_success = false;

            $this->report($this->position, '" "');
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position67;

            if (substr($this->string, $this->position, strlen("\t")) === "\t") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("\t"));
                $this->position += strlen("\t");
            } else {
                $_success = false;

                $this->report($this->position, '"\\t"');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position67;

            if (substr($this->string, $this->position, strlen("\n")) === "\n") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("\n"));
                $this->position += strlen("\n");
            } else {
                $_success = false;

                $this->report($this->position, '"\\n"');
            }
        }

        if (!$_success && !$this->cut) {
            $this->position = $_position67;

            if (substr($this->string, $this->position, strlen("\n")) === "\n") {
                $_success = true;
                $this->value = substr($this->string, $this->position, strlen("\n"));
                $this->position += strlen("\n");
            } else {
                $_success = false;

                $this->report($this->position, '"\\n"');
            }
        }

        $this->cut = $_cut68;

        if ($_success) {
            $_value70 = array($this->value);
            $_cut71 = $this->cut;

            while (true) {
                $_position69 = $this->position;

                $this->cut = false;
                $_position67 = $this->position;
                $_cut68 = $this->cut;

                $this->cut = false;
                if (substr($this->string, $this->position, strlen(" ")) === " ") {
                    $_success = true;
                    $this->value = substr($this->string, $this->position, strlen(" "));
                    $this->position += strlen(" ");
                } else {
                    $_success = false;

                    $this->report($this->position, '" "');
                }

                if (!$_success && !$this->cut) {
                    $this->position = $_position67;

                    if (substr($this->string, $this->position, strlen("\t")) === "\t") {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, strlen("\t"));
                        $this->position += strlen("\t");
                    } else {
                        $_success = false;

                        $this->report($this->position, '"\\t"');
                    }
                }

                if (!$_success && !$this->cut) {
                    $this->position = $_position67;

                    if (substr($this->string, $this->position, strlen("\n")) === "\n") {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, strlen("\n"));
                        $this->position += strlen("\n");
                    } else {
                        $_success = false;

                        $this->report($this->position, '"\\n"');
                    }
                }

                if (!$_success && !$this->cut) {
                    $this->position = $_position67;

                    if (substr($this->string, $this->position, strlen("\n")) === "\n") {
                        $_success = true;
                        $this->value = substr($this->string, $this->position, strlen("\n"));
                        $this->position += strlen("\n");
                    } else {
                        $_success = false;

                        $this->report($this->position, '"\\n"');
                    }
                }

                $this->cut = $_cut68;

                if (!$_success) {
                    break;
                }

                $_value70[] = $this->value;
            }

            if (!$this->cut) {
                $_success = true;
                $this->position = $_position69;
                $this->value = $_value70;
            }

            $this->cut = $_cut71;
        }

        if ($_success) {
            $this->value = strval(substr($this->string, $_position72, $this->position - $_position72));
        }

        if ($_success) {
            $chars = $this->value;
        }

        if ($_success) {
            $this->value = call_user_func(function () use (&$chars) {
                return new Space($chars);
            });
        }

        $this->cache['SPACE'][$_position] = array(
            'success' => $_success,
            'position' => $this->position,
            'value' => $this->value
        );

        if (!$_success) {
            $this->report($_position, 'SPACE');
        }

        return $_success;
    }

    private function line()
    {
        if (!empty($this->errors)) {
            $positions = array_keys($this->errors);
        } else {
            $positions = array_keys($this->warnings);
        }

        return count(explode("\n", substr($this->string, 0, max($positions))));
    }

    private function rest()
    {
        return '"' . substr($this->string, $this->position) . '"';
    }

    protected function report($position, $expecting)
    {
        if ($this->cut) {
            $this->errors[$position][] = $expecting;
        } else {
            $this->warnings[$position][] = $expecting;
        }
    }

    private function expecting()
    {
        if (!empty($this->errors)) {
            ksort($this->errors);

            return end($this->errors)[0];
        }

        ksort($this->warnings);

        return implode(', ', end($this->warnings));
    }

    public function parse($_string)
    {
        $this->string = $_string;
        $this->position = 0;
        $this->value = null;
        $this->cache = array();
        $this->cut = false;
        $this->errors = array();
        $this->warnings = array();

        $_success = $this->parseTREE();

        if ($_success && $this->position < strlen($this->string)) {
            $_success = false;

            $this->report($this->position, "end of file");
        }

        if (!$_success) {
            throw new \InvalidArgumentException("Syntax error, expecting {$this->expecting()} on line {$this->line()}");
        }

        return $this->value;
    }
}