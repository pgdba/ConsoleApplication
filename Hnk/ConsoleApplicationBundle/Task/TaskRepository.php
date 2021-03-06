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
     * @param  TaskInterface $task
     *
     * @return TaskIdentifier
     *
     * @throws \Exception
     */
    public function storeTask(TaskInterface $task)
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
     * @param  TaskInterface $task
     *
     * @return string
     *
     * @throws \Exception
     */
    protected function generateTaskId(TaskInterface $task)
    {
        $sanitizedName = str_replace(array(' ', '-'), '_', $task->getName());

        $id = $sanitizedName;

        $i = 1;
        do {
            if (!$this->hasTask($id)) {
                return $id;
            }
            $id = $sanitizedName . ++$i;
        } while ($i < 100);

        throw new \Exception(sprintf('Cannot create id for task %s', $task->getName()));
    }
}
