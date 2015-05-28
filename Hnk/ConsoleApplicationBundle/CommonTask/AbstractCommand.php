<?php

namespace Hnk\ConsoleApplicationBundle\CommonTask;

use Hnk\ConsoleApplicationBundle\Task\CommonTask;

abstract class AbstractCommand extends CommonTask
{
    /**
     * @var array
     */
    protected $commandOptions = array();

    /**
     * @var bool
     */
    protected $useSudo = false;

    /**
     * @return array
     */
    public function getCommandOptions()
    {
        return $this->commandOptions;
    }

    /**
     * @param  array $commandOptions
     *
     * @return $this
     */
    public function setCommandOptions($commandOptions)
    {
        $this->commandOptions = $commandOptions;

        return $this;
    }

    /**
     * @param  string $commandOption
     *
     * @return $this
     */
    public function addCommandOption($commandOption)
    {
        if (!in_array($commandOption, $this->commandOptions)) {
            $this->commandOptions[] = $commandOption;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getCommandOptionString()
    {
        $optionString = '';
        foreach($this->commandOptions as $option) {
            if (0 !== strpos($option, '-')) {
                $option = '-' . $option;
            }

            $optionString .= ' ' . $option;
        }

        return $optionString;
    }

    /**
     * @param  bool $useSudo
     *
     * @return $this
     */
    public function sudo($useSudo = true)
    {
        $this->useSudo = $useSudo;

        return $this;
    }

    /**
     * @param  string $command
     *
     * @return string
     */
    protected function getCommand($command)
    {
        if ($this->useSudo) {
            $command = 'sudo ' . $command;
        }

        return $command;
    }
}
