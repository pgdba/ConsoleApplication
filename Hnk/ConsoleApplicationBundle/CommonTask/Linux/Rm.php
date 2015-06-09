<?php

namespace Hnk\ConsoleApplicationBundle\CommonTask\Linux;

use Hnk\ConsoleApplicationBundle\CommonTask\AbstractCommand;

class Rm extends AbstractCommand
{
    const BASE_NAME = 'rm';

    /**
     * @var array
     */
    protected $files = array();

    /**
     * @return null
     */
    public function handler()
    {
        $command = sprintf(
            '%s%s %s',
            $this->getCommand('rm'),
            $this->getCommandOptionString(),
            join(' ', $this->files)
        );

        $this->getHelper()->runCommand($command, $this->getRunDirectory());
    }

    /**
     * @return null
     *
     * @throws \Exception
     */
    public function checkReady()
    {
        if (empty($this->files)) {
            throw new \Exception('Set files for rm');
        }
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param  string|array $files
     *
     * @return $this
     */
    public function setFiles($files)
    {
        if (!$files) {
            return $this;
        }

        if (!is_array($files)) {
            $files = array($files);
        }

        $this->files = $files;

        return $this;
    }

    /**
     * @return $this
     */
    public function force()
    {
        $this->addCommandOption('f');

        return $this;
    }

    /**
     * @return $this
     */
    public function recursive()
    {
        $this->addCommandOption('r');

        return $this;
    }
}
