<?php

namespace Hnk\ConsoleApplicationBundle\CommonTask\Linux;

use Hnk\ConsoleApplicationBundle\CommonTask\AbstractCommand;

class Chmod extends AbstractCommand
{
    const BASE_NAME = 'chmod';

    /**
     * @var string
     */
    protected $mode = '0777';

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
            '%s%s %s %s',
            $this->getCommand('chmod'),
            $this->getCommandOptionString(),
            $this->mode,
            join(' ', $this->files)
        );

        $this->getHelper()->runCommand($command);
    }

    /**
     * @return null
     *
     * @throws \Exception
     */
    public function checkReady()
    {
        if (empty($this->files)) {
            throw new \Exception('Set files for chmod');
        }
    }

    /**
     * @return string
     */
    public function getMode()
    {
        return $this->mode;
    }

    /**
     * @param  string $mode
     *
     * @return $this
     */
    public function setMode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return $this->files;
    }

    /**
     * @param  array $files
     *
     * @return $this
     */
    public function setFiles($files)
    {
        $this->files = $files;

        return $this;
    }
}
