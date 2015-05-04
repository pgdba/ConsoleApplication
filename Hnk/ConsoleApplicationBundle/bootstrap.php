<?php

define('HNK_CONSOLE_APPLICATION_APP_DIR', __DIR__);
define('HNK_CONSOLE_APPLICATION_BASE_DIR', dirname(dirname(HNK_CONSOLE_APPLICATION_APP_DIR)));

define('DEBUG_DIR', dirname(dirname(HNK_CONSOLE_APPLICATION_BASE_DIR)).'/Debug/Hnk/Debug');

if (is_dir(DEBUG_DIR) && file_exists(DEBUG_DIR.'/bootstrap.php')) {
    define('HNK_DEBUG_MODE', 'develop');

    require_once DEBUG_DIR.'/bootstrap.php';
}

require_once HNK_CONSOLE_APPLICATION_APP_DIR . '/autoload.php';
