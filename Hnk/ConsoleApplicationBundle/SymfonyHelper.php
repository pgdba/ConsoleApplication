<?php

namespace Hnk\ConsoleApplicationBundle;

/**
 * @author Jakub Rapacz
 * @package Hnk\ConsoleApplication
 */
class SymfonyHelper extends Helper {

    /**
     * @var SymfonyHelper
     */
    private static $instance = null;
    
    /**
     * @return SymfonyHelper
     */
    public static function getInstance() 
    {
        if (null === self::$instance) {
            self::$instance = new SymfonyHelper();
        }
        
        return self::$instance;
    }
    
    /**
     * Renders bundle choice
     *
     * @param array     $bundles
     * @param string    $defaultBundle
     * @return array|false
     */
    public function renderBundleChoice(array $bundles, $defaultBundle = null)
    {
        $defaultChoice = null;
        
        $this->println('Available bundles:');
        foreach ($bundles as $key => $bundle) {
            $this->println(sprintf(" * %s: %s", $bundle['name'], $this->decorateText($key, self::COLOR_YELLOW)));
            if ($bundle['name'] == $defaultBundle) {
                $defaultChoice = $key;
            }
        }

        $this->println();
        $choice = $this->readln("Choose bundle:", $defaultChoice);
        $this->println();

        if (array_key_exists($choice, $bundles)) {
            return $bundles[$choice];
        } else {
            return false;
        }
    }

    /**
     * @param array $environments
     * @return bool
     */
    public function renderEnvironmentChoice(array $environments, $defaultEnvironment = null)
    {
        $defaultChoice = null;
        
        $this->println('Available environments:');
        foreach ($environments as $key => $env) {
            $this->println(sprintf(" * %s: %s", $env, $this->decorateText($key, self::COLOR_YELLOW)));
            if ($env == $defaultEnvironment) {
                $defaultChoice = $key;
            }
        }

        $this->println();
        $choice = $this->readln("Choose environment:", $defaultChoice);
        $this->println();

        if (array_key_exists($choice, $environments)) {
            return $environments[$choice];
        } else {
            return false;
        }
    }
    
    /**
     * @param string $srcDir
     * 
     * @return array
     */
    public function getBundles($srcDir) 
    {
        $bundles = array();
        $this->recurrentBundleFinder($srcDir, $bundles);

        return $bundles;
    }

    /**
     * Looks for Symfony bundles in $srcDir
     * Use $bundles to pass found bundles
     *
     * @param $srcDir
     * @param array $bundles
     * @param string $path
     * @param int $depth
     * @param int $maxDepth
     */
    public function recurrentBundleFinder($srcDir, &$bundles, $path = '', $depth = 1, $maxDepth = 4)
    {
        $srcDir = rtrim($srcDir, '/') . '/';
        
        if ($depth > $maxDepth) {
            return;
        }

        if (!$path) {
            $files = $this->getFilesInDir($srcDir, true);
            if ($files) {
                foreach ($files as $file) {
                    $this->recurrentBundleFinder($srcDir, $bundles, $file . '/', $depth + 1, $maxDepth);
                }
            } else {
                return;
            }
        } else {
            $bundleName = str_replace('/', '', $path);

            if (file_exists($srcDir . $path . $bundleName . '.php')) { // TODO - create more sophisticated hack
                $key = count($bundles) + 1;
                $bundles[$key] = array('name' => $bundleName, 'path' => $path);
                return;
            } else {
                $files = $this->getFilesInDir($srcDir . $path, true);
                if ($files) {
                    foreach ($files as $file) {
                        $this->recurrentBundleFinder($srcDir, $bundles, $path . $file . '/', $depth + 1, $maxDepth);
                    }
                }
            }
        }
        return;
    }
} 
