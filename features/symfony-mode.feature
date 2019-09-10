Feature: Running in symfony mode

  Scenario: I complete a standard symfony command
    Given a script named "comphlete.php":
        """
        $application = new \Symfony\Component\Console\Application();

        $application->add(new \hanneskod\comphlete\Symfony\ComphleteCommand);

        $application->run();
        """
    When I run "php comphlete.php  _comphlete 'a h' 2"
    Then the output is "help"

  Scenario: I change the definition of a standard symfony command
    Given a script named "comphlete.php":
        """
        namespace hanneskod\comphlete\Symfony;

        class Processor implements DefinitionProcessor
        {
            public function processDefinition(SymfonyConsoleDefinition $definition): SymfonyConsoleDefinition
            {
                $helpDef = $definition->getContext('help');

                $helpDef->addArgument(1, ['foobar']);

                return $definition;
            }
        }

        $application = new \Symfony\Component\Console\Application();

        $application->add(new ComphleteCommand(new Processor));

        $application->run();
        """
    When I run "php comphlete.php  _comphlete 'a help f' 8"
    Then the output is "foobar"

  Scenario: I generate a bash load script
    Given a script named "comphlete.php":
        """
        $application = new \Symfony\Component\Console\Application();

        $application->add(new \hanneskod\comphlete\Symfony\ComphleteCommand);

        $application->run();
        """
    When I run "php comphlete.php  _comphlete --generate-bash-script --app-name=comphletetest"
    And I collect the output as '$output'
    And I expand and run "bash $output"
    Then there is no error
