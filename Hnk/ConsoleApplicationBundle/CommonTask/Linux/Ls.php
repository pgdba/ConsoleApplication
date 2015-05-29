<?php

namespace Hnk\ConsoleApplicationBundle\CommonTask\Linux;

use Hnk\ConsoleApplicationBundle\CommonTask\AbstractCommand;

class Ls extends AbstractCommand
{
    const BASE_NAME = 'ls';

    /**
     * @var string
     */
    protected $directory;

    /**
     * @return null
     */
    public function handler()
    {
        $command = sprintf(
            '%s%s',
            $this->getCommand('ls'),
            $this->getCommandOptionString()
        );

        $this->getHelper()->runCommand($command, $this->directory);
    }

    /**
     * @return null
     *
     * @throws \Exception
     */
    public function checkReady()
    {
        if (null === $this->directory) {
            throw new \Exception('Directory not set');
        }
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * @param  string $directory
     *
     * @return $this
     */
    public function setDirectory($directory)
    {
        $this->directory = $directory;

        return $this;
    }
}
