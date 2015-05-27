<?php

namespace Hnk\ConsoleApplicationBundle\Task;

use Hnk\ConsoleApplicationBundle\Exception\UnknownMenuItemException;
use Hnk\ConsoleApplicationBundle\Helper\TaskHelper;
use Hnk\ConsoleApplicationBundle\Menu\MenuItemInterface;
use Hnk\ConsoleApplicationBundle\Menu\MenuProviderInterface;

class TaskGroup implements TaskInterface, MenuProviderInterface, TaskRepositoryAwareInterface
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
     * @var TaskIdentifier[]
     */
    protected $tasks = array();

    /**
     * @var TaskRepository
     */
    protected $taskRepository;

    /**
     * @var TaskHelper
     */
    protected $helper;

    /**
     * @param string        $name
     * @param array         $options
     * @param string        $description
     * @param TaskInterface $parent
     */
    public function __construct($name, $options = array(), $description = '', TaskInterface $parent = null)
    {
        $this->name = $name;
        $this->options = $options;
        $this->description = $description;
        $this->parent = null;
        $this->helper = TaskHelper::getInstance();
    }

    /**
     * @param  TaskInterface $task
     * @param  string         $key
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function addTask(TaskInterface $task, $key = null)
    {

        if (false === $this->isReady()) {
            throw new \Exception('TaskGroup is not ready');
        }

        if ($task instanceof TaskRepositoryAwareInterface) {
            $task->setTaskRepository($this->taskRepository);
        }

        $taskIdentifier = $this->taskRepository->storeTask($task);

        $key = $this->getKeyForTask($key);
        $this->addTaskIdentifier($taskIdentifier, $key);

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
     * @return TaskIdentifier
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

    /**
     * @param  MenuItemInterface $item
     * @param  null              $key
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function addItem(MenuItemInterface $item, $key = null)
    {
        if ($item instanceof TaskIdentifier) {
            $this->addTaskIdentifier($item, $key);
        } elseif ($item instanceof TaskInterface) {
            $this->addTask($item, $key);
        } else {
            throw \Exception(sprintf('Invalid item type: %s', get_class($item)));
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return TaskRepository
     */
    public function getTaskRepository()
    {
        return $this->taskRepository;
    }

    /**
     * @param  TaskRepositoryInterface $taskRepository
     *
     * @return null
     */
    public function setTaskRepository(TaskRepositoryInterface $taskRepository)
    {
        $this->taskRepository = $taskRepository;
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
     * @param TaskIdentifier $task
     * @param string|null    $key
     */
    protected function addTaskIdentifier(TaskIdentifier $task, $key = null)
    {
        $key = $this->getKeyForTask($key);

        $this->tasks[$key] = $task;
    }

    /**
     * @param string|int $key
     * @param int       $try
     *
     * @return string|int
     *
     * @throws \Exception
     */
    protected function getKeyForTask($key, $try = 1)
    {
        if (30 < $try) {
            throw new \Exception('Unable to generate key for task');
        }

        if (!$key || false === (is_string($key) || is_numeric($key))) {
            $key = $this->getKeyForTask((count($this->tasks) + 1), ++$try);
        } else {
            if (in_array($key, array_keys($this->tasks))) {
                if (is_numeric($key)) {
                    $key++;
                } else {
                    $key = $key . '1';
                }
                $key = $this->getKeyForTask($key, ++$try);
            }
        }

        return $key;
    }

    /**
     * Checks if TaskGroup is ready for task storing
     *
     * @return bool
     */
    protected function isReady()
    {
        return null !== $this->taskRepository;
    }
}
