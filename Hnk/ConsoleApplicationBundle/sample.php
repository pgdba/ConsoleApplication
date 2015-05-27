#!/usr/bin/php
<?php

use Hnk\ConsoleApplicationBundle\App;


define('DEBUG_DIR', dirname(dirname(HNK_CONSOLE_APPLICATION_BASE_DIR)).'/Debug/Hnk/Debug');

if (is_dir(DEBUG_DIR) && file_exists(DEBUG_DIR.'/bootstrap.php')) {
    define('HNK_DEBUG_MODE', 'develop');

    require_once DEBUG_DIR.'/bootstrap.php';
}

require_once __DIR__ . '/bootstrap.php';
$GLOBALS['dev'] = 0;
$appOptions = array(
    App::OPTION_CACHE_DIR => __DIR__ . '/.hcaCache',
    App::OPTION_TASK_FILE => __DIR__ . '/sample_tasks.php',
);

// main application definition
$app = new App($appOptions);
$app->run();
