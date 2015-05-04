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

    public function __construct()
    {
        $cacheFile = __DIR__ .'/.hcaCache';

        $this->choice = new Choice();

        $this->stateManager = new StateManager(array('saveFile' => $cacheFile));
        $this->state = $this->stateManager->getState();
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
        RenderHelper::println();
        RenderHelper::println(RenderHelper::decorateText($task->getName(), RenderHelper::COLOR_GREEN));
        if ($task->getDescription()) {
            RenderHelper::println($task->getDescription());
        }
        RenderHelper::println();

        if ($task instanceof MenuProviderInterface) {
            $menuHandler = new MenuHandler(TaskHelper::getInstance());

            if ($lastChoice = $this->state->getChoiceStack()->getFirst()) {
                $lastTask = $lastChoice->getChoiceTask()
                    ->setName($lastChoice->getChoiceName());
                $task->addItem($lastTask);
            }

            $selectedItem = $menuHandler->handle($task);

            if ($selectedItem instanceof TaskAbstract) {
                $this->handleChoice($selectedItem);
                $this->handleTask($selectedItem);
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
}
