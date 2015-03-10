<?php

namespace Hnk\ConsoleApplicationBundle;

/**
 * @author Jakub Rapacz
 * @package Hnk\ConsoleApplication
 */
class SymfonyProject extends Project {

    /**
     * @var array
     */
    protected $bundles = [];

    /**
     * @var array 
     */
    protected $environments = [
        1 => 'dev', 
        2 => 'prod', 
        3 => 'test'
    ];

    /**
     * @var array
     */
    protected $isLoaded = [
        'bundles' => false
    ];

    /**
     * @var string
     */
    protected $srcPath;

    /**
     * @param string $name
     * @param string $path
     * @throws Exception
     */
    function __construct($name, $path)
    {
        parent::__construct($name, $path);
        
        $srcPath = $this->path.'/src';
        if (!is_dir($srcPath)) {
            throw new Exception(sprintf('Cannot find src directory in path %s', $this->path));
        }
        $this->srcPath = $srcPath;
    }
    
    /**
     * Lazy loading
     * @return array
     */
    public function getBundles()
    {
        if (false === $this->isLoaded['bundles']) {
            $this->loadBundles();
        }

        return $this->bundles;
    }

    protected function loadBundles()
    {
        $symfonyHelper = SymfonyHelper::getInstance();
        $bundles = $symfonyHelper->getBundles($this->srcPath);
        $this->setBundles($bundles);
        $this->isLoaded['bundles'] = true;
    }

    /**
     * @return array
     */
    public function getEnvironments()
    {
        return $this->environments;
    }

    /**
     * @return string
     */
    public function getSrcPath()
    {
        return $this->srcPath;
    }

    /**
     * @param array $bundles
     * 
     * @return $this
     */
    public function setBundles($bundles)
    {
        $this->bundles = $bundles;
        
        return $this;
    }

    /**
     * @param array $environments
     * 
     * @return $this
     */
    public function setEnvironments($environments)
    {
        $this->environments = $environments;
        
        return $this;
    }

    /**
     * @param string $srcPath
     * 
     * @return $this
     */
    public function setSrcPath($srcPath)
    {
        $this->srcPath = $srcPath;
        
        return $this;
    }
} 
