<?php

namespace Hnk\ConsoleApplicationBundle;

use Hnk\ConsoleApplicationBundle\Helper\RenderHelper;
use Hnk\ConsoleApplicationBundle\Helper\TaskHelper;
use Hnk\ConsoleApplicationBundle\Menu\MenuHandler;
use Hnk\ConsoleApplicationBundle\Menu\MenuProviderInterface;
use Hnk\ConsoleApplicationBundle\Task\RunnableTaskInterface;
use Hnk\ConsoleApplicationBundle\Task\TaskAbstract;

class App
{
    /**
     * @var TaskAbstract
     */
    protected $task;

    /**
     *
     */
    public function run()
    {
        $this->handleTask($this->task);
    }

    /**
     * @param  TaskAbstract $task
     *
     * @throws \Exception
     */
    protected function handleTask(TaskAbstract $task)
    {
        RenderHelper::println();
        RenderHelper::println(RenderHelper::decorateText($task->getName(), RenderHelper::COLOR_GREEN));
        if ($task->getDescription()) {
            RenderHelper::println($task->getDescription());
        }
        RenderHelper::println();

        if ($task instanceof MenuProviderInterface) {
            $menuHandler = new MenuHandler(TaskHelper::getInstance());

            $selectedItem = $menuHandler->handle($task);

            if ($selectedItem instanceof TaskAbstract) {
                $this->handleTask($selectedItem);
            }

            if (null === $selectedItem) {
                if ($task->hasParent()) { // todo
                    $this->handleTask($task->getParent());
                } else {
                    RenderHelper::println('EXIT');
                    exit;
                }
            }
        } elseif ($task instanceof RunnableTaskInterface) {
            $task->run();
        }
    }

    /**
     * @param  TaskAbstract $task
     *
     * @return $this
     */
    public function setTask(TaskAbstract $task)
    {
        $this->task = $task;

        return $this;
    }
}
