# comphlete

Dynamic bash completion from PHP

## Why?

Say you have a cli script to read some data on a customer, using comphlete you
can autocomplete customer ids directly from the command line. It's both fun and
powerful. And works well with symfony console apps.

## Installation

```shell
composer require hanneskod/comphlete
```

## Usage

### With symfony apps

...

### The hard way

Create your autocomplete definition in a php script (here named `comphlete.php`).

```php
$definition = (new hanneskod\comphlete\Definition)
    // first argument with a fixed set of suggestions
    ->addArgument(0, ['foo', 'bar', 'baz'])

    // second argument with a dynamic callback
    ->addArgument(1, function () {
        // load suggestions from database...
        return ['aa', 'bb'];
    })

    // simple option
    ->addOption('foo')

    // option with suggested values
    ->addOption('bar', ['val1', 'val2'])
;

$completer = new Completer($definition);

$input = (new InputFactory)->createFromArgv($argv);

echo implode(' ', $completer->complete($input));
```

This script generates suggestions from a command line and a cursor position.
Running it as

```shell
php comphlete.php "command f" 8
```

should output `foo`.

> NOTE `command` is the name of the command we are autocompleting, it is simply
> ignored, and can be anything at this point.

To load inte you environment create a script like

```bash
#/usr/bin/env bash

_mycommand_comphletions() {
    COMPREPLY=($(compgen -W "$(php comphlete.php "${COMP_LINE}" "${COMP_POINT}")"))
}

complete -o default -F _mycommand_comphletions mycommand
```

### Using contexts

A common design pattern is to have an application define a number of commands
with there own sets of arguments and options. Comphlete supports this by the use
of contexts. Here is an app with an `import` and an `export` command.

```php
$import = (new ContextDefinition('import'))
    ->addArgument(2, ['some-argument'])
;

$export = (new ContextDefinition('export'))
    ->addArgument(2, ['another-argument'])
;

$def = (new ContextContainerDefinition)
    ->addContext($import)
    ->addContext($export)
;

$completer = new Completer($def);

$input = (new InputFactory)->createFromArgv($argv);

echo implode(' ', $completer->complete($input));
```
