<?php

namespace Hnk\ConsoleApplicationBundle\Task;

class TaskRepositoryFactory
{
    /**
     * TODO - dorobić jakies cache
     *
     * @return TaskRepository
     */
    public function getTaskRepository()
    {
        return new TaskRepository();
    }
}
