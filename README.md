<?php

namespace hanneskod\comphlete;

include "vendor/autoload.php";

// TODO install instructions!
//
// bin/cmd to create shell script from values...
// source script.sh to .bashrc ...

use hanneskod\comphlete\Input;
use hanneskod\comphlete\Matcher;

$completer = new Autocompleter(
    new Matcher\ContextMatcher([
        'edit' => new Matcher\ArgumentsAndOptionsMatcher(
            new Matcher\ArgumentsMatcher(
                new Matcher\DictionaryMatcher(
                    new Dictionary(new Word('foo'), new Word('bar'), new Word('baz'))
                ),
                new Matcher\CallbackMatcher(function () {
                    return new Dictionary(new Word('hannes'), new Word('ebbe'), new Word('emma'));
                })
            ),
            new Matcher\OptionMatcher(
                new Dictionary(new Word('--foo'), new Word('-f'))
            )
        ),
    ])
);


// TODO factory som kan skapa setup från något sånt här...
// kan ju såklart tänka oss att generera från symfony på något sätt..
$donorMatcher = null;

$def = [
    '.global-options' => [
        '--help',
    ],
    'show' => [
        [$donorMatcher],
        ['--id'],
    ]
];

echo $completer->complete(Input\Input::fromArgv($argv));
