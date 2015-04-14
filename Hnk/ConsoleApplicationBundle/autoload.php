<?php

spl_autoload_register(function($class)
{
    $classPath = str_replace('\\', '/', $class);
    $fullPath = sprintf('%s/%s.php', HNK_CONSOLE_APPLICATION_BASE_DIR, $classPath);

    if (file_exists($fullPath)) {
        require_once $fullPath;
    } 
});

