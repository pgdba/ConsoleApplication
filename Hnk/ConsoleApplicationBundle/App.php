<?php

namespace Hnk\ConsoleApplicationBundle;

use Hnk\ConsoleApplicationBundle\Helper\RenderHelper;
use Hnk\ConsoleApplicationBundle\Helper\TaskHelper;
use Hnk\ConsoleApplicationBundle\Menu\MenuHandler;
use Hnk\ConsoleApplicationBundle\Menu\MenuProviderInterface;
use Hnk\ConsoleApplicationBundle\Task\RunnableTaskInterface;
use Hnk\ConsoleApplicationBundle\Task\TaskAbstract;
use Hnk\ConsoleApplicationBundle\Task\TaskIdentifier;
use Hnk\ConsoleApplicationBundle\Task\TaskRepository;
use Hnk\ConsoleApplicationBundle\Task\TaskRepositoryFactory;

class App
{
    /**
     * @var TaskAbstract
     */
    protected $task;

    /**
     * @var TaskRepository
     */
    protected $taskRepository;

    /**
     * @var TaskHelper
     */
    protected $taskHelper;

    public function __construct()
    {
        $taskRepositoryFactory = new TaskRepositoryFactory();
        $this->taskRepository = $taskRepositoryFactory->getTaskRepository();
        $this->taskHelper = TaskHelper::getInstance();
    }

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
        $this->taskHelper->renderTaskHeader($task->getName(), $task->getDescription());

        if ($task instanceof MenuProviderInterface) {
            $menuHandler = new MenuHandler($this->taskHelper);

            $selectedItem = $menuHandler->handle($task);

            if ($selectedItem instanceof TaskIdentifier) {
                $selectedTask = $this->taskRepository->getTask($selectedItem);
                $this->handleTask($selectedTask);
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

    /**
     * @return TaskRepository
     */
    public function getTaskRepository()
    {
        return $this->taskRepository;
    }
}
