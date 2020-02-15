<?php

declare(strict_types = 1);

namespace hanneskod\comphlete;

use hanneskod\comphlete\LineParser\Tree;
use hanneskod\comphlete\Suggester\SuggesterInterface;
use hanneskod\comphlete\Suggester\CombinedSuggester;
use hanneskod\comphlete\Suggester\ArgumentSuggester;
use hanneskod\comphlete\Suggester\OptionSuggester;
use hanneskod\comphlete\Suggester\OptionValueSuggester;

final class Definition implements DefinitionInterface
{
    /** @var array<int, array|callable> */
    private $arguments = [];

    /** @var array<string, string> */
    private $options = [];

    /** @var array<string, array|callable> */
    private $optionValues = [];

    /**
     * @param array<string>|callable $suggestions
     */
    public function addArgument(int $argument, $suggestions = []): self
    {
        $this->arguments[$argument] = $suggestions;

        return $this;
    }

    /**
     * @param array<string>|callable|null $suggestions
     */
    public function addOption(string $option, $suggestions = null): self
    {
        $option = '--' . $option;

        if (is_null($suggestions)) {
            $this->options[$option] = $option . ' ';
            $this->optionValues[$option] = [];
            return $this;
        }

        $this->options[$option] = $option . '=';
        $this->optionValues[$option] = $suggestions;

        return $this;
    }

    public function getSuggester(Tree $tree): SuggesterInterface
    {
        return new CombinedSuggester(
            new ArgumentSuggester($this->arguments),
            new OptionSuggester(array_values($this->options)),
            new OptionValueSuggester($this->optionValues)
        );
    }
}
