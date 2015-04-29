<?php

namespace Hnk\ConsoleApplicationBundle\Task;

use Hnk\ConsoleApplicationBundle\Exception\UnknownMenuItemException;
use Hnk\ConsoleApplicationBundle\Menu\MenuProviderInterface;

class TaskGroup extends TaskAbstract implements MenuProviderInterface
{
    /**
     * @var array
     */
    protected $tasks = array();

    /**\
     * @param  TaskAbstract $task
     * @param  string       $key
     *
     * @return $this
     */
    public function addTask(TaskAbstract $task, $key = null)
    {
        if (!$key || false === (is_string($key) || is_numeric($key))) {
            $key = count($this->tasks) + 1;
        }

        $this->tasks[$key] = $task;
        $task->setParent($this);

        return $this;
    }

    /**
     * @return bool
     */
    public function hasTasks()
    {
        return !empty($this->tasks);
    }

    /**
     * Get choice list for selection
     *
     * @return array   [string => mixed]
     */
    public function getItems()
    {
        return $this->tasks;
    }

    /**
     * Return selected item
     *
     * @param  string $choice
     *
     * @return TaskAbstract
     *
     * @throws UnknownMenuItemException
     */
    public function getSelectedItem($choice)
    {
        if (!array_key_exists($choice, $this->tasks)) {
            throw new UnknownMenuItemException(sprintf('Item %s does not exist in task group %s', $choice, $this->name));
        }

        return $this->tasks[$choice];
    }
}