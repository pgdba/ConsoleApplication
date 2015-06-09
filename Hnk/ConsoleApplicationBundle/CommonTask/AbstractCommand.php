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
     * @var ProjectInterface
     */
    protected $project;

    /**
     * @var string
     */
    protected $workPath = null;

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

    /**
     * @return ProjectInterface
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @param  ProjectInterface $project
     *
     * @return $this
     */
    public function setProject(ProjectInterface $project)
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return string
     */
    public function getWorkPath()
    {
        return $this->workPath;
    }

    /**
     * @param  string $workPath
     *
     * @return $this
     */
    public function setWorkPath($workPath)
    {
        $this->workPath = $workPath;

        return $this;
    }

    /**
     * @return null|string
     */
    public function getRunDirectory()
    {
        $path = null;

        if (null !== $this->workPath) {
            $path = $this->workPath;
        } else {
            if (null !== $this->project) {
                $path = $this->project->getPath();
            }
        }

        // TODO -sanitize path
//        if (null !== $path) {
//            $path = (strpos)
//        }

        return $path;
    }
}
