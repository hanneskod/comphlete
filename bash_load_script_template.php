<?php

// Usage from command line: php bash_load_script_template.php my-app-name
// Usage as regular template: set variables and do a regular include

$argv = $argv ?? [];

if (!is_array($argv)) {
    throw new Exception('If present $argv must be an array');
}

$appName = $appName ?? ($argv[1] ?? '');
$subCommand = $subCommand ?? ($argv[2] ?? '');
$ifs = $ifs ?? ($argv[3] ?? '|');

if (!$appName) {
    throw new Exception('Application name missing');
}

?>
#/usr/bin/env bash

_<?=$appName?>_completions() {
    IFS='<?=$ifs?>'
    for reply in $(<?=$appName?> <?=$subCommand?> "${COMP_LINE}" "${COMP_POINT}")
    do
        COMPREPLY+=($reply)
    done
}

complete -o default -o nospace -F _<?=$appName?>_completions <?=$appName?>
