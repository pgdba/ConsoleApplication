<?php

namespace Hnk\ConsoleApplicationBundle\Task;

class TaskRepository implements TaskRepositoryInterface
{
    /**
     * @var TaskAbstract[]
     */
    protected $tasks;

    public function __construct()
    {
        $this->tasks = array();
    }

    /**
     * @param  TaskAbstract $task
     *
     * @return TaskIdentifier
     *
     * @throws \Exception
     */
    public function storeTask(TaskAbstract $task)
    {
        $id = $this->generateTaskId($task);
        $this->tasks[$id] = $task;

        return new TaskIdentifier($id, $task->getName());
    }

    /**
     * @param  TaskIdentifier|string $identifier
     *
     * @return TaskAbstract
     *
     * @throws \Exception
     */
    public function getTask($identifier)
    {
        $id = ($identifier instanceof TaskIdentifier) ? $identifier->getId() : $identifier;

        if (!$this->hasTask($id)) {
            throw new \Exception(sprintf('No task with id %s', $id));
        }

        return $this->tasks[$id];
    }

    /**
     * @param  TaskIdentifier|string $identifier
     *
     * @return bool
     */
    public function hasTask($identifier)
    {
        $id = ($identifier instanceof TaskIdentifier) ? $identifier->getId() : $identifier;

        return array_key_exists($id, $this->tasks);
    }

    /**
     * @param  TaskAbstract $task
     *
     * @return string
     *
     * @throws \Exception
     */
    protected function generateTaskId(TaskAbstract $task)
    {
        $id = md5($task->getName());

        $i = 1;
        do {
            if (!$this->hasTask($id)) {
                return $id;
            }
            $id = md5($task->getName() . $i);
        } while ($i < 100);

        throw new \Exception(sprintf('Cannot create id for task %s', $task->getName()));
    }
}
