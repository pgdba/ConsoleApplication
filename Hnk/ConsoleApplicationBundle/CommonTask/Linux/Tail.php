<?php

namespace Hnk\ConsoleApplicationBundle\CommonTask\Linux;

use Hnk\ConsoleApplicationBundle\CommonTask\AbstractCommand;
use Hnk\ConsoleApplicationBundle\Task\TaskInterface;

class Tail extends AbstractCommand
{
    const BASE_NAME = 'tail';

    /**
     * @var array
     */
    protected $files = array();

    /**
     * @param string        $name
     * @param array         $options
     * @param string        $description
     * @param TaskInterface $parent
     */
    public function __construct($name = '', $options = array(), $description = '', TaskInterface $parent = null)
    {
        parent::__construct($name, $options, $description, $parent);
        $this->commandOptions[] = 'f'; // default behavior
    }

    /**
     * @return null
     */
    public function handler()
    {
        $command = sprintf(
            '%s%s %s',
            $this->getCommand('tail'),
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
            throw new \Exception('Set files for tail');
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
}
