<?php

namespace Hnk\ConsoleApplicationBundle\Task;

class TaskRepositoryFactory
{
    /**
     * @var TaskRepositoryFactory
     */
    public static $instance;

    /**
     * @return TaskRepositoryFactory
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new TaskRepositoryFactory();
        }

        return self::$instance;
    }

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
