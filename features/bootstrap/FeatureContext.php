<?php

declare(strict_types = 1);

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

class FeatureContext implements Context
{
    /** @var ShellWrapper */
    private $shell;

    /** @var Result Result from the last app invocation */
    private $result;

    /** @var string[] */
    private $collectedValues = [];

    public function __construct()
    {
        $this->shell = new ShellWrapper;
    }

    /**
     * @Given a script named :name:
     */
    public function aScriptNamed(string $name, PyStringNode $script)
    {
        $script = sprintf(
            "<?php\nnamespace autoload;\ninclude '%s';\n%s",
            realpath(__DIR__ . '/../../vendor/autoload.php'),
            (string)$script
        );

        $this->shell->createFile($name, $script);
    }

    /**
     * @When I run :command
     */
    public function iRun(string $command)
    {
        $this->result = $this->shell->execute($command);
    }

    /**
     * @When I collect the output as :variable
     */
    public function iCollectTheOutputAs(string $variable)
    {
        $this->thereIsNoError();
        $this->collectedValues[$variable] = $this->result->getOutput();
    }

    /**
     * @When I expand and run :command
     */
    public function iExpandAndRun(string $command)
    {
        $this->iRun(
            str_replace(array_keys($this->collectedValues), $this->collectedValues, $command)
        );
    }

    /**
     * @Then there is no error
     */
    public function thereIsNoError()
    {
        if ($this->result->isError()) {
            throw new \Exception("Error: {$this->result->getErrorOutput()}");
        }
    }

    /**
     * @Then the output is :expected
     */
    public function theOutputIs(string $expected)
    {
        $this->thereIsNoError();

        if ($expected != $this->result->getOutput()) {
            throw new \Exception("Unexpected output '{$this->result->getOutput()}', expecting '$expected'");
        }
    }
}
