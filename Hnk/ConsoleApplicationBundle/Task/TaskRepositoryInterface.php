<?php

namespace Hnk\ConsoleApplicationBundle\Task;

interface TaskRepositoryInterface
{
    /**
     * @param  string $id
     *
     * @return TaskAbstract
     */
    public function getTask($id);
}
