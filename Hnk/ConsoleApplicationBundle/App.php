<?php

namespace Hnk\ConsoleApplicationBundle;

use Hnk\ConsoleApplicationBundle\Exception\MenuException;
use Hnk\ConsoleApplicationBundle\Helper\RenderHelper;
use Hnk\ConsoleApplicationBundle\Helper\TaskHelper;
use Hnk\ConsoleApplicationBundle\Menu\MenuHandler;
use Hnk\ConsoleApplicationBundle\Menu\MenuProviderInterface;
use Hnk\ConsoleApplicationBundle\State\Choice;
use Hnk\ConsoleApplicationBundle\State\StateManager;
use Hnk\ConsoleApplicationBundle\Task\RunnableTaskInterface;
use Hnk\ConsoleApplicationBundle\Task\TaskGroup;
use Hnk\ConsoleApplicationBundle\Task\TaskIdentifier;
use Hnk\ConsoleApplicationBundle\Task\TaskInterface;
use Hnk\ConsoleApplicationBundle\Task\TaskRepository;
use Hnk\ConsoleApplicationBundle\Task\TaskRepositoryFactory;

class App
{
    const OPTION_CACHE_DIR = 'cacheDir';
    const OPTION_TASK_FILE = 'taskFile';

    /**
     * @var TaskGroup
     */
    protected $taskGroup;

    /**
     * @var Choice
     */
    protected $choice;

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
        $this->taskRepository = TaskRepositoryFactory::getInstance()->getTaskRepository();
        $this->taskHelper = TaskHelper::getInstance();

        $this->initTaskGroup();

        $this->loadTaskFile();
    }

    /**
     *
     */
    public function run()
    {
        $this->addLastChoice();

        $this->handleTask($this->taskGroup);
    }

    /**
     *
     */
    protected function addLastChoice()
    {
        $lastChoiceLimit = 3;
        $keys = array('q', 'w', 'e');
        $lastChoices = $this->stateManager->getState()->getChoiceStack()->getChoices(0, $lastChoiceLimit);

        $index = 1;
        $isFirst = true;
        foreach($lastChoices as $choice) {
            if (null !== $choice) {
                $menuOptions = array('noCache' => true, 'menuLabel' => 'LAST '. $index .': ');

                // TODO - shitty hack, needs fixing
                $task = $choice->getChoiceTask();

                if (null === $task) {
                    continue;
                }

                $task = clone $task; // wtf...

                if ($isFirst) {
                    $menuOptions['extraSpace'] = true;
                    $isFirst = false;
                }

                $task->setName($choice->getChoiceName())
                    ->setOption('menuOptions', $menuOptions);

                $this->taskGroup->addItem($task, $keys[$index]);

                $index++;
            }
        }
    }

    /**
     * @param  TaskInterface $task
     * @param  int           $deep
     *
     * @throws \Exception
     */
    protected function handleTask(TaskInterface $task, $deep = 1)
    {
        $this->taskHelper->renderTaskHeader($task->getName(), $task->getDescription());

        if ($task instanceof MenuProviderInterface) {
            $this->doHandleMenuTask($task, $deep);
        } elseif ($task instanceof RunnableTaskInterface) {
            $task->run();
            $this->choice->setHasRunnableTask(true);
        }

        $this->onClose();
        exit;
    }

    /**
     * @param MenuProviderInterface $task
     * @param int                   $deep
     *
     * @throws \Exception
     */
    protected function doHandleMenuTask(MenuProviderInterface $task, $deep)
    {
        $menuHandler = new MenuHandler($this->taskHelper);

        try {
            $selectedItem = $menuHandler->handle($task);
        } catch (MenuException $e) {
            RenderHelper::printError($e->getMessage());
            return;
        }

        if ($selectedItem instanceof TaskIdentifier) {
            $this->handleChoice($selectedItem);
            $selectedTask = $this->taskRepository->getTask($selectedItem);
            $this->handleTask($selectedTask, ++$deep);
        }

        if (null === $selectedItem) {
            $previousTaskId = $this->choice->getPreviousTaskId();

            if (null !== $previousTaskId) { // todo
                $this->choice->removeLastChoiceTask();
                $this->handleTask($this->getTaskRepository()->getTask($previousTaskId), --$deep);
            } elseif (2 === $deep) {
                $this->handleTask($this->taskGroup);
            } else {
                RenderHelper::println('EXIT');
                return;
            }
        }
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

    protected function onClose()
    {
        if ($this->choice->hasRunnableTask()) {
            $this->stateManager->getState()->getChoiceStack()->addChoice($this->choice);
        }
        $this->stateManager->saveState();
    }

    /**
     *
     */
    protected function initTaskGroup()
    {
        $this->taskGroup = new TaskGroup('ConsoleApplication');
        $this->taskGroup->setTaskRepository($this->taskRepository);
    }


    /**
     * @return TaskRepository
     */
    public function getTaskRepository()
    {
        return $this->taskRepository;
    }

    /**
     * @return TaskGroup
     */
    public function getTaskGroup()
    {
        return $this->taskGroup;
    }
}
