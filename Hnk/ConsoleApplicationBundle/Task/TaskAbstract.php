<?php

namespace Hnk\ConsoleApplicationBundle\Task;

use Hnk\ConsoleApplicationBundle\Helper\TaskHelper;
use Hnk\ConsoleApplicationBundle\Menu\MenuItemInterface;

abstract class TaskAbstract implements MenuItemInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var TaskAbstract
     */
    protected $parent;

    /**
     * @var TaskHelper
     */
    protected $helper;

    /**
     * @param string       $name
     * @param array        $options
     * @param string       $description
     * @param TaskAbstract $parent
     */
    public function __construct($name, $options = array(), $description = '', TaskAbstract $parent = null)
    {
        $this->name = $name;
        $this->options = $options;
        $this->description = $description;
        $this->parent = null;
        $this->helper = TaskHelper::getInstance();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param  string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param  string $description
     *
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * @param  string $key
     * @param  mixed  $defaultValue
     * @param  bool   $isRequired
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function getOption($key, $defaultValue = null, $isRequired = false)
    {
        $optionExists = array_key_exists($key, $this->options);

        if ($isRequired && !$optionExists) {
            throw new \Exception(sprintf('Required option %s doesn\'t exist', $key));
        }

        return ($optionExists) ? $this->options[$key] : $defaultValue;
    }

    /**
     * @param  string $key
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function requireOption($key)
    {
        return $this->getOption($key, null, true);
    }

    /**
     * @param  array $options
     *
     * @return $this
     */
    public function setOptions($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * @param  string $key
     * @param  mixed  $value
     *
     * @return $this
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;

        return $this;
    }

    /**
     * @return TaskAbstract
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param  TaskAbstract $parent
     *
     * @return $this
     */
    public function setParent(TaskAbstract $parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasParent()
    {
        return ($this->parent instanceof TaskAbstract);
    }

    /**
     * @return TaskHelper
     */
    public function getHelper()
    {
        return $this->helper;
    }

    /**
     * @param mixed $task
     *
     * @return bool
     */
    public function equals($task)
    {
        return $task === $this;
    }

    /**
     * @return array
     */
    public function getMenuOptions()
    {
        return (isset($this->options['menuOptions'])) ? $this->options['menuOptions'] : array();
    }
}
