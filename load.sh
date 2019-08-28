#/usr/bin/env bash

_dothis_completions()
{
    COMPREPLY=($(compgen -W "$(php README.md "${COMP_LINE}" "${COMP_CWORD}")"))
}

complete -o default -F _dothis_completions a
