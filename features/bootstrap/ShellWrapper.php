<?php

declare(strict_types = 1);

class ShellWrapper
{
    /** @var string */
    private $cwd;

    /** @var callable */
    private $debugDump;

    public function __construct(bool $debug = false)
    {
        $this->cwd = sys_get_temp_dir() . '/comphlete_test_' . microtime();

        mkdir($this->cwd);

        $this->debugDump = function (string $str, string $pre = '') use ($debug) {
            if ($debug) {
                foreach (explode(PHP_EOL, $str) as $line) {
                    if (!empty($line)) {
                        echo "$pre $line\n";
                    }
                }
            }
        };
    }

    public function __destruct()
    {
        if (is_dir($this->cwd)) {
            exec("rm -rf '{$this->cwd}'");
        }
    }

    public function execute(string $command): Result
    {
        ($this->debugDump)($command, '$');

        $process = proc_open(
            $command,
            [
                1 => ["pipe", "w"],
                2 => ["pipe", "w"]
            ],
            $pipes,
            $this->cwd
        );

        $output = stream_get_contents($pipes[1]);
        $errorOutput = stream_get_contents($pipes[2]);

        ($this->debugDump)($output, '>');
        ($this->debugDump)($errorOutput, '2>');

        fclose($pipes[1]);
        fclose($pipes[2]);

        $returnCode = proc_close($process);

        return new Result($returnCode, $output, $errorOutput);
    }

    public function createFile(string $name, string $content)
    {
        file_put_contents("{$this->cwd}/$name", $content);
    }
}
