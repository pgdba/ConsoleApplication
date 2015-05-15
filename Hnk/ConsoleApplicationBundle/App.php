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
use Hnk\ConsoleApplicationBundle\Task\TaskGroup;
use Hnk\ConsoleApplicationBundle\Task\TaskIdentifier;
use Hnk\ConsoleApplicationBundle\Task\TaskRepository;
use Hnk\ConsoleApplicationBundle\Task\TaskRepositoryFactory;

class App
{
    const OPTION_CACHE_DIR = 'cacheDir';
    const OPTION_TASK_FILE = 'taskFile';

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

    /**
     * @var array
     */
    protected $options;

    /**
     * @param  array $options
     *
     * @throws \Exception
     */
    public function __construct($options = array())
    {
        $this->validateOptions($options);
        $this->options = $options;

        $this->checkCache();

        $this->choice = new Choice();
        $this->stateManager = new StateManager(array('saveFile' => $this->options[self::OPTION_CACHE_DIR] . '/state'));
        $this->state = $this->stateManager->getState();
        $taskRepositoryFactory = new TaskRepositoryFactory();
        $this->taskRepository = $taskRepositoryFactory->getTaskRepository();
        $this->taskHelper = TaskHelper::getInstance();

        $this->loadTaskFile();
    }

    /**
     *
     */
    public function run()
    {
        if ($this->task instanceof TaskGroup && $lastChoice = $this->state->getChoiceStack()->getFirst()) {
            $lastTask = $lastChoice->getChoiceTask()
                ->setName($lastChoice->getChoiceName());
            $lastTask->setOption('menuOptions', array('extraSpace' => true, 'noCache' => true, 'menuLabel' => 'LAST: '));
            $this->task->addItem($lastTask, 'q');
        }

        $this->handleTask($this->task);
    }

    /**
     * @param  TaskAbstract $task
     * @param  int          $deep
     *
     * @throws \Exception
     */
    protected function handleTask(TaskAbstract $task, $deep = 1)
    {
        $this->taskHelper->renderTaskHeader($task->getName(), $task->getDescription());

        if ($task instanceof MenuProviderInterface) {
            $menuHandler = new MenuHandler($this->taskHelper);

            $selectedItem = $menuHandler->handle($task);

            if ($selectedItem instanceof TaskIdentifier) {
                $this->handleChoice($selectedItem);
                $selectedTask = $this->taskRepository->getTask($selectedItem);
                $this->handleTask($selectedTask, ++$deep);
            }

            if (null === $selectedItem) {
                $previousTaskId = $this->choice->getPreviousTaskId();

                if (null !== $previousTaskId) { // todo
                    $this->choice->removeLastChoiceTask();
                    d($this->choice, 'after remove');
                    $this->handleTask($this->getTaskRepository()->getTask($previousTaskId), --$deep);
                } elseif (2 === $deep) {
                    $this->handleTask($this->task);
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

    protected function handleChoice(TaskIdentifier $task)
    {
        $options = $task->getMenuOptions();
        if (isset($options['noCache'])) {
            return;
        }

        $lastChoice = $this->choice->getLastChild();

        if ($lastChoice->hasTask()) {
            $newChoice = new Choice();
            $lastChoice->setChild($newChoice);
            $lastChoice = $newChoice;
        }

        $lastChoice->setTask($task);
    }

    /**
     * TODO - change this method
     *
     * @param  $options
     *
     * @throws \Exception
     */
    protected function validateOptions($options)
    {
        $requiredOptions = array(self::OPTION_CACHE_DIR, self::OPTION_TASK_FILE);

        foreach($requiredOptions as $option) {
            if (!array_key_exists($option, $options) || !$options[self::OPTION_CACHE_DIR]) {
                throw new \Exception(sprintf('Option %s is required', self::OPTION_CACHE_DIR));
            }
        }
    }

    /**
     * TODO
     */
    protected function checkCache()
    {
        if (!is_dir($this->options[self::OPTION_CACHE_DIR])) {
            mkdir($this->options[self::OPTION_CACHE_DIR], 0777);
        }
    }

    /**
     * TODO
     */
    protected function loadTaskFile()
    {
        $app = $this;
        require_once $this->options[self::OPTION_TASK_FILE];
    }

    /**
     * TODO
     */
    protected function onClose()
    {
        if ($this->choice->hasTask()) {
            $this->state->getChoiceStack()->addChoice($this->choice);
        }
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
