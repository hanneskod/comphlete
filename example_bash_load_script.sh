#/usr/bin/env bash

_comphlete_completions() {
    COMPREPLY=($(compgen -W "$(php myscript.php "${COMP_LINE}" "${COMP_POINT}")"))
}

complete -o default -F _comphlete_completions a
