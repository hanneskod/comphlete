Feature: Running in default mode

  Scenario: I complete an argument
    Given a script named "comphlete.php":
        """
        namespace hanneskod\comphlete;

        $definition = (new Definition)
            ->addArgument(0, ['foo', 'bar', 'baz'])
        ;

        $completer = new Completer($definition);

        $input = (new InputFactory)->createFromArgv($argv);

        echo implode(' ', $completer->complete($input));
        """
    When I run "php comphlete.php 'a' 2"
    Then the output is "foo bar baz"

  Scenario: I complete a started argument
    Given a script named "comphlete.php":
        """
        namespace hanneskod\comphlete;

        $definition = (new Definition)
            ->addArgument(0, ['foo', 'bar', 'baz'])
        ;

        $completer = new Completer($definition);

        $input = (new InputFactory)->createFromArgv($argv);

        echo implode(' ', $completer->complete($input));
        """
    When I run "php comphlete.php 'a f' 3"
    Then the output is "foo"

  Scenario: I complete a dynamic argument
    Given a script named "comphlete.php":
        """
        namespace hanneskod\comphlete;

        $definition = (new Definition)
            ->addArgument(0, function () {
                // load suggestions from database...
                return ['aa', 'bb'];
            })
        ;

        $completer = new Completer($definition);

        $input = (new InputFactory)->createFromArgv($argv);

        echo implode(' ', $completer->complete($input));
        """
    When I run "php comphlete.php 'a a' 2"
    Then the output is "aa"

  Scenario: I complete an option
    Given a script named "comphlete.php":
        """
        namespace hanneskod\comphlete;

        $definition = (new Definition)
            ->addOption('foo')
        ;

        $completer = new Completer($definition);

        $input = (new InputFactory)->createFromArgv($argv);

        echo implode(' ', $completer->complete($input));
        """
    When I run "php comphlete.php 'a --' 4"
    Then the output is "--foo"

  Scenario: I complete an option value
    Given a script named "comphlete.php":
        """
        namespace hanneskod\comphlete;

        $definition = (new Definition)
            ->addOption('bar', ['val1', 'val2'])
        ;

        $completer = new Completer($definition);

        $input = (new InputFactory)->createFromArgv($argv);

        echo implode(' ', $completer->complete($input));
        """
    When I run "php comphlete.php 'a --bar=' 8"
    Then the output is "val1 val2"

  Scenario: I complete a context name
    Given a script named "comphlete.php":
        """
        namespace hanneskod\comphlete;

        $def = (new ContextContainerDefinition)
            ->addContext(new ContextDefinition('import'))
            ->addContext(new ContextDefinition('export'))
        ;

        $completer = new Completer($def);

        $input = (new InputFactory)->createFromArgv($argv);

        echo implode(' ', $completer->complete($input));
        """
    When I run "php comphlete.php 'a' 2"
    Then the output is "import export"

  Scenario: I complete a context argument
    Given a script named "comphlete.php":
        """
        namespace hanneskod\comphlete;

        $export = (new ContextDefinition('export'))
            ->addArgument(1, ['arg'])
        ;

        $def = (new ContextContainerDefinition)
            ->addContext(new ContextDefinition('import'))
            ->addContext($export)
        ;

        $completer = new Completer($def);

        $input = (new InputFactory)->createFromArgv($argv);

        echo implode(' ', $completer->complete($input));
        """
    When I run "php comphlete.php 'a export a' 10"
    Then the output is "arg"

  Scenario: I complete an unknown
   Given a script named "comphlete.php":
       """
       namespace hanneskod\comphlete;

       $completer = new Completer(new Definition);

       $input = (new InputFactory)->createFromArgv($argv);

       echo implode(' ', $completer->complete($input));
       """
   When I run "php comphlete.php 'a unknown' 5"
   Then the output is ""
