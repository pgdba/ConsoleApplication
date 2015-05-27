#!/usr/bin/php
<?php

use Hnk\ConsoleApplicationBundle\App;

require_once __DIR__ . '/bootstrap.php';

$appOptions = array(
    App::OPTION_CACHE_DIR => __DIR__ . '/.hcaCache',
    App::OPTION_TASK_FILE => __DIR__ . '/sample_tasks.php',
);

// main application definition
$app = new App($appOptions);
$app->run();
