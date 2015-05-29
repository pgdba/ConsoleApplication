<?php

namespace Hnk\ConsoleApplicationBundle\Task;

abstract class CommonTask extends TaskAbstract implements RunnableTaskInterface
{
    /**
     * Task base name - e.g. 'vagrant up' or 'cache clear'
     *
     * Having BASE_NAME -> 'cache clear' and $name -> 'in my project', getName will return 'cache clear in my project'
     *
     */
    const BASE_NAME = '';

    /**
     * @param string        $name
     * @param array         $options
     * @param string        $description
     * @param TaskInterface $parent
     */
    public function __construct($name = '', $options = array(), $description = '', TaskInterface $parent = null)
    {
        parent::__construct($name, $options, $description, $parent);
    }

    /**
     * @return null
     */
    abstract public function handler();

    /**
     * @return null
     *
     * @throws \Exception
     */
    abstract public function checkReady();

    /**
     * @return string
     */
    public function getName()
    {
        if ('' !== static::BASE_NAME) {
            return static::BASE_NAME . ' '. $this->name;
        }

        return $this->name;
    }

    /**
     * @return null
     */
    public function run()
    {
        $this->checkReady();

        $this->handler();
    }
}
