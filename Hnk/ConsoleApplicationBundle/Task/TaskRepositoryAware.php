<?php

namespace Hnk\ConsoleApplicationBundle\Task;

interface TaskRepositoryAware
{
    /**
     * @return TaskRepository
     */
    public function getTaskRepository();

    /**
     * @param  TaskRepositoryInterface $taskRepository
     *
     * @return null
     */
    public function setTaskRepository(TaskRepositoryInterface $taskRepository);
}
