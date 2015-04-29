<?php

namespace Hnk\ConsoleApplicationBundle\Helper;

use Hnk\ConsoleApplicationBundle\Exception\NotADirectoryException;

class FileHelper
{
    /**
     * @param  string $dir
     * @param  bool   $onlyDirectories
     *
     * @return array
     *
     * @throws NotADirectoryException
     */
    public static function getFilesInDir($dir, $onlyDirectories = false)
    {
        $dir = rtrim($dir, '/') . '/';

        $files = array();
        if (is_dir($dir)) {
            $handler = opendir($dir);
            while (false !== ($file = readdir($handler))) {
                if ($file != "." && $file != "..") {
                    if ($onlyDirectories && is_dir($dir . $file)) {
                        $files[] = $file;
                    } elseif (!$onlyDirectories) {
                        $files[] = $file;
                    }
                }
            }
            closedir($handler);
        } else {
            throw new NotADirectoryException(sprintf("%s is not a directory", $dir));
        }

        return $files;
    }
}
