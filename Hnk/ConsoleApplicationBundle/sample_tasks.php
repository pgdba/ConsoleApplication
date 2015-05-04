<?php

use Hnk\ConsoleApplicationBundle\Task\Task;
use Hnk\ConsoleApplicationBundle\Task\TaskGroup;

$mainApp = new TaskGroup('ConsoleApplication');
$mainApp->setDescription(sprintf('Sample console application.%sBuild You\'re commands in file %s and run them from the menu below.', PHP_EOL, __FILE__));
$app->setTask($mainApp);


// basic command definition
$mainApp->addTask($app->getTaskRepository()->storeTask(new Task('pwd', function(Task $task) {
    $task->getHelper()->runCommand('pwd', HNK_CONSOLE_APPLICATION_APP_DIR);
}, array(), 'Runs pwd on ConsoleApplicationBundle directory')));

// more advanced command
$lsApp = new TaskGroup('ls');
$lsApp->addTask($app->getTaskRepository()->storeTask(new Task('ls in HNK_CONSOLE_APPLICATION_APP_DIR', function($a){$a->getHelper()->runCommand('ls', HNK_CONSOLE_APPLICATION_APP_DIR);})), 1);
$lsApp->addTask($app->getTaskRepository()->storeTask(new Task('ls in HNK_CONSOLE_APPLICATION_BASE_DIR', function($a){$a->getHelper()->runCommand('ls', HNK_CONSOLE_APPLICATION_BASE_DIR);})), 2);

$deeperLs = new TaskGroup('deeper ls');
$deeperLs->addTask($app->getTaskRepository()->storeTask(new Task('deeper ls', function($a){$a->getHelper()->runCommand('ls');})));
$lsApp->addTask($app->getTaskRepository()->storeTask($deeperLs));
$mainApp->addTask($app->getTaskRepository()->storeTask($lsApp), 2);
