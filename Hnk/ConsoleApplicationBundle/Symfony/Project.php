<?php

namespace Hnk\ConsoleApplicationBundle\Symfony;

use Hnk\ConsoleApplicationBundle\CommonTask\ProjectInterface;

class Project implements ProjectInterface
{
    const ENVIRONMENT_DEV = 1;
    const ENVIRONMENT_PROD = 2;
    const ENVIRONMENT_TEST = 3;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var BundleProvider
     */
    protected $bundleProvider;

    /**
     * @var array
     */
    protected $environments = array(
        self::ENVIRONMENT_DEV => 'dev',
        self::ENVIRONMENT_PROD => 'prod',
        self::ENVIRONMENT_TEST => 'test'
    );

    /**
     * @var string
     */
    protected $srcPath;

    /**
     * @param  string $name
     * @param  string $path
     *
     * @throws \Exception
     */
    public function __construct($name, $path)
    {
        $this->name = $name;
        $this->path = $path;
        $this->srcPath = $this->path.'/src';
        if (!is_dir($this->srcPath)) {
            throw new \Exception(sprintf('Cannot find src directory in path %s', $this->path));
        }
        $this->bundleProvider = new BundleProvider($this->srcPath);
    }

    /**
     * @return Bundle[]
     */
    public function getBundles()
    {
        return $this->bundleProvider->getBundles();
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
     * @param  string $key
     * @param  string $name
     *
     * @return $this
     */
    public function addEnvironment($key, $name)
    {
        $this->environments[$key] = $name;

        return $this;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }
}
