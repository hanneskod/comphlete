# comphlete

[![Packagist Version](https://img.shields.io/packagist/v/hanneskod/comphlete.svg?style=flat-square)](https://packagist.org/packages/hanneskod/comphlete)
[![Build Status](https://img.shields.io/travis/hanneskod/comphlete/master.svg?style=flat-square)](https://travis-ci.org/hanneskod/comphlete)

Dynamic bash completion from PHP

## Why?

Say you have a cli script to read some data, using comphlete you can
autocomplete data identifiers directly from the command line. It's both fun and
powerful. And works well with symfony console apps.

## Installation

```shell
composer require hanneskod/comphlete
```

## Usage

### With symfony apps

Create an empty symfony console application (here named `myapp.php`).

```php
$application = new \Symfony\Component\Console\Application();

$application->add(new \hanneskod\comphlete\Symfony\ComphleteCommand);

$application->run();
```

This creates a hidden command named `_complete` that handles autocompletion.

To register autocompletion in your enviroment use (in `.bashrc`)

```shell
source $(myapp.php _complete --generate-bash-script --app-name=myapp.php)
```

> NOTE that the `ComphleteCommand` does not work for single command applications.
> If your application is a single command app you'll have to revert to the
> default way of createing suggestions. See below.

### The (not so) hard way

Create your autocomplete definition in a php script (here named `test.php`).

```php
namespace hanneskod\comphlete;

$definition = (new Definition)
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

echo Helper::dump($completer->complete($input));
```

To load into you environment create a bash script (note that this requires
`test.php` to be in you `PATH` to work properly).

```shell
php bash_load_script_template.php test.php > load.sh
```

And source it (in `.bashrc`)

```shell
source load.sh
```

### Using contexts

A common design pattern is to have an application define a number of commands
with their own sets of arguments and options. Comphlete supports this by the use
of contexts. Here is an app with an `import` and an `export` command.

```php
$import = (new ContextDefinition('import'))
    ->addArgument(1, ['some-argument'])
;

$export = (new ContextDefinition('export'))
    ->addArgument(1, ['another-argument'])
;

$def = (new ContextContainerDefinition)
    ->addContext($import)
    ->addContext($export)
;

$completer = new Completer($def);

$input = (new InputFactory)->createFromArgv($argv);

echo Helper::dump($completer->complete($input));
```
