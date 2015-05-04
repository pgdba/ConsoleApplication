<?php

namespace Hnk\ConsoleApplicationBundle;

use Hnk\ConsoleApplicationBundle\Helper\RenderHelper;
use Hnk\ConsoleApplicationBundle\Helper\TaskHelper;
use Hnk\ConsoleApplicationBundle\Menu\MenuHandler;
use Hnk\ConsoleApplicationBundle\Menu\MenuProviderInterface;
use Hnk\ConsoleApplicationBundle\State\Choice;
use Hnk\ConsoleApplicationBundle\State\ChoiceStack;
use Hnk\ConsoleApplicationBundle\State\State;
use Hnk\ConsoleApplicationBundle\State\StateManager;
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
     * @var Choice
     */
    protected $choice;

    /**
     * @var State
     */
    protected $state;

    /**
     * @var StateManager
     */
    protected $stateManager;

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
        $cacheFile = __DIR__ .'/.hcaCache';

        $this->choice = new Choice();

        $this->stateManager = new StateManager(array('saveFile' => $cacheFile));
        $this->state = $this->stateManager->getState();

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

            if ($lastChoice = $this->state->getChoiceStack()->getFirst()) {
                $lastTask = $lastChoice->getChoiceTask()
                    ->setName($lastChoice->getChoiceName());
                $task->addItem($lastTask);
            }

            $selectedItem = $menuHandler->handle($task);

            if ($selectedItem instanceof TaskIdentifier) {
                $selectedTask = $this->taskRepository->getTask($selectedItem);
                $this->handleTask($selectedTask);
                $this->handleChoice($selectedItem);
            }

            if (null === $selectedItem) {
                if ($task->hasParent()) { // todo
                    $this->handleTask($task->getParent());
                } else {
                    $this->onClose();
                    RenderHelper::println('EXIT');
                    exit;
                }
            }
        } elseif ($task instanceof RunnableTaskInterface) {
            $task->run();
            $this->onClose();
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

    protected function handleChoice(TaskAbstract $task)
    {
        $this->choice->setTask($task);

        $newChoice = new Choice();
        $newChoice->setParent($this->choice);

        $this->choice = $newChoice;
    }

    protected function onClose()
    {
        $this->state->getChoiceStack()->addChoice($this->choice);
        $this->stateManager->saveState($this->state);
    }

    /**
     * @return TaskRepository
     */
    public function getTaskRepository()
    {
        return $this->taskRepository;
    }
}
