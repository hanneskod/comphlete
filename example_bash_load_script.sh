#/usr/bin/env bash

_comphlete_completions() {
    IFS='|'
    for reply in $(php test.php "${COMP_LINE}" "${COMP_POINT}")
    do
        COMPREPLY+=($reply)
    done
}

complete -o default -o nospace -F _comphlete_completions a
