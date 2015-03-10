#!/usr/bin/php
<?php

use Hnk\ConsoleApplicationBundle\Command;
use Hnk\ConsoleApplicationBundle\SymfonyCommand;
use Hnk\ConsoleApplicationBundle\SymfonyProject;

require_once __DIR__ . '/bootstrap.php';

// main application definition
$mainApp = new Command('ConsoleApplication');
$mainApp->setDescription(sprintf('Sample console application.%sBuild You\'re commands in file %s and run them from the menu below.', PHP_EOL, __FILE__));

// basic command definition
$mainApp->addChild((new Command(
        'pwd', 
        function(Command $a) {
            $a->getHelper()->runCommand('pwd', APP_DIR);
        }
    ))->setDescription('Runs pwd on ConsoleApplicationBundle directory')
    , 1);

// more advanced command
$lsApp = new Command('ls');
$lsApp->addChild(new Command('ls in APP_DIR', function($a){$a->getHelper()->runCommand('ls', APP_DIR);}, false), 1);
$lsApp->addChild(new Command('ls in BASE_DIR', function($a){$a->getHelper()->runCommand('ls', BASE_DIR);}, false), 2);
$mainApp->addChild($lsApp, 2);    

// symfony project
$bookingProject = new SymfonyProject('booking', '/home/unenc/booking/git/bookings-api');
$mainApp->addChild(new SymfonyCommand('cache clear', $bookingProject, function(SymfonyCommand $a){
    $env = $a->renderEnvironmentChoice('dev');
//    $a->runConsoleCommand(sprintf('cache:clear --env=%s', $env));
    $bundle = $a->renderBundleChoice('AccountApiBundle');
    
    $a->getHelper()->println(sprintf('Env: %s, bundle: %s', $env, $bundle['name']));
    $a->getHelper()->renderConfirm();
}));

$mainApp->run();
