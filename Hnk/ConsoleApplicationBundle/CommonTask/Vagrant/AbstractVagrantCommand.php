<?php

namespace Hnk\ConsoleApplicationBundle\CommonTask\Vagrant;

use Hnk\ConsoleApplicationBundle\CommonTask\AbstractCommand;

abstract class AbstractVagrantCommand extends AbstractCommand
{
    /**
     * @var string
     */
    protected $projectDirectory = '';

    /**
     * @return null
     *
     * @throws \Exception
     */
    public function checkReady()
    {
        if ('' === $this->projectDirectory) {
            throw new \Exception('Project dir is not set');
        }

        if (!is_dir($this->projectDirectory)) {
            throw new \Exception(sprintf('Path %s is not a directory or does not exist', $this->projectDirectory));
        }
    }

    /**
     * @return string
     */
    public function getProjectDirectory()
    {
        return $this->projectDirectory;
    }

    /**
     * @param  string $projectDirectory
     *
     * @return $this
     */
    public function setProjectDirectory($projectDirectory)
    {
        $this->projectDirectory = $projectDirectory;

        return $this;
    }

    protected function runSimpleVagrantCommand($command)
    {
        $command = sprintf(
            '%s %s%s',
            $this->getCommand('vagrant'),
            $command,
            $this->getCommandOptionString()
        );

        $this->getHelper()->runCommand($command, $this->projectDirectory);
    }
}
