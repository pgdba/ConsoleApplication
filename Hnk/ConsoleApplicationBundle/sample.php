#!/usr/bin/php
<?php

use Hnk\ConsoleApplicationBundle\App;
use Hnk\ConsoleApplicationBundle\Helper\RenderHelper;
use Hnk\ConsoleApplicationBundle\Symfony\Project;
use Hnk\ConsoleApplicationBundle\Symfony\SymfonyTask;
use Hnk\ConsoleApplicationBundle\Task\Task;
use Hnk\ConsoleApplicationBundle\Task\TaskGroup;


require_once __DIR__ . '/bootstrap.php';

// main application definition
$app = new App();
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
$mainApp->addTask($app->getTaskRepository()->storeTask($lsApp), 2);

// symfony project
//$bookingProject = new Project('booking', '/home/unenc/booking/git/bookings-api');
//$mainApp->addTask(new SymfonyTask('cache clear', $bookingProject, function(SymfonyTask $a){
//    $env = $a->getHelper()->renderEnvironmentChoice($a->getProject()->getBundles(), 'dev');
////    $a->runConsoleCommand(sprintf('cache:clear --env=%s', $env));
//    $bundle = $a->getHelper()->renderBundleChoice($a->getProject()->getBundles(), 'AccountApiBundle');
//
//    RenderHelper::println(sprintf('Env: %s, bundle: %s', $env, $bundle['name']));
//    $a->getHelper()->renderConfirm();
//}));

$app->run();
