<?php

use Hnk\ConsoleApplicationBundle\CommonTask\Linux\Ls;
use Hnk\ConsoleApplicationBundle\CommonTask\Linux\Tail;
use Hnk\ConsoleApplicationBundle\Task\Task;
use Hnk\ConsoleApplicationBundle\Task\TaskGroup;

$mainApp = $app->getTaskGroup();
$mainApp->setDescription(sprintf('Sample console application.%sBuild You\'re commands in file %s and run them from the menu below.', PHP_EOL, __FILE__));

// basic command definition
$mainApp->addTask(new Task('pwd', function(Task $task) {
    $task->getHelper()->runCommand('pwd', HNK_CONSOLE_APPLICATION_APP_DIR);
}, array(), 'Runs pwd on ConsoleApplicationBundle directory'));

// more advanced command
$lsApp = new TaskGroup('ls');

// add task group before storing tasks in it, it will receive TaskRepository instance or ...
$mainApp->addTask($lsApp, 5);
//$lsApp->setTaskRepository($mainApp->getTaskRepository()); // ... or set TaskRepository yourself

$lsApp->addTask(new Task('ls in HNK_CONSOLE_APPLICATION_APP_DIR', function($a){$a->getHelper()->runCommand('ls', HNK_CONSOLE_APPLICATION_APP_DIR);}), 1);
$lsApp->addTask(new Task('ls in HNK_CONSOLE_APPLICATION_BASE_DIR', function($a){$a->getHelper()->runCommand('ls', HNK_CONSOLE_APPLICATION_BASE_DIR);}), 2);

$lsCommon = new Ls('in main dir');
$lsApp->addTask(
    $lsCommon
        ->setDirectory('/')
        ->setOptions(array('l', 'a'))
);

$deeperLs = new TaskGroup('deeper ls');
$lsApp->addTask($deeperLs);
$deeperLs->addTask(new Task('deeper ls', function($a){$a->getHelper()->runCommand('ls');}));


// common tasks
$tailApp = new TaskGroup('tail');
$mainApp->addTask($tailApp);

$tailSingle = new Tail('single file');
$tailApp->addTask(
    $tailSingle->setFiles(array('~/.bash_profile'))
);
