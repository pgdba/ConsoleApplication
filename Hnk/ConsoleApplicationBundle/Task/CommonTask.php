<?php

namespace Hnk\ConsoleApplicationBundle\Task;

abstract class CommonTask extends TaskAbstract implements RunnableTaskInterface
{
    /**
     * @return null
     */
    abstract public function handler();

    /**
     * @return null
     */
    public function run()
    {
        $this->handler();
    }
}
