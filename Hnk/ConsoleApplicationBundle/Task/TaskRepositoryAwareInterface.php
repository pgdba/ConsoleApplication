<?php

namespace Hnk\ConsoleApplicationBundle\Task;

interface TaskRepositoryAwareInterface
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
