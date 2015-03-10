<?php

namespace Hnk\ConsoleApplicationBundle;

class SymfonyCommand extends Command
{
    /** 
     * @var SymfonyProject 
     */
    protected $symfonyProject;


    /**
     * @param string         $name
     * @param SymfonyProject $project
     * @param callable       $handler
     * @param array          $settings
     */
    function __construct($name, SymfonyProject $project, \Closure $handler = null, $settings = [])
    {
        parent::__construct($name, $handler, $settings);
        $this->symfonyProject = $project;
    }
    
    /**
     * @param string $command
     * @return mixed
     */
    public function runConsoleCommand($command, $consolePath = null)
    {
        if (null === $consolePath) {
            $consolePath = $this->symfonyProject->getPath();
        }

        return $this->getHelper()
                ->runCommand(
                    sprintf('app/console %s', $command), $consolePath
                );
    }
    
    /**
     * @param string $defaultEnvironment
     * 
     * @return string
     */
    public function renderEnvironmentChoice($defaultEnvironment = null)
    {
        return $this->getHelper()
                ->renderEnvironmentChoice(
                    $this->getSymfonyProject()->getEnvironments(),
                    $defaultEnvironment
                );
    }
    
    /**
     * @param string|null $defaultBundle
     * 
     * @return array
     */
    public function renderBundleChoice($defaultBundle = null)
    {
        return $this->getHelper()
                ->renderBundleChoice(
                    $this->getSymfonyProject()->getBundles(),
                    $defaultBundle
                );
    }

    /**
     * @return SymfonyProject
     */
    public function getSymfonyProject()
    {
        return $this->symfonyProject;
    }
        
    /**
     * @return SymfonyHelper
     */
    public function getHelper()
    {
        return SymfonyHelper::getInstance();
    }
}
