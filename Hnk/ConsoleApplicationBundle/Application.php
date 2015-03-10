<?php

namespace Hnk\ConsoleApplicationBundle;

/**
 * @author Jakub Rapacz
 */
class Application
{
    /**
     * @var string 
     */
    protected $name;
    
    /**
     * @var array
     */
    protected $children = [];
    
    /**
     * @var \Closure|null
     */
    protected $handler = null;

    /**
     * @var Application|null
     */
    protected $parent = null;

    /**
     * @var bool
     */
    protected $exitAfterRun = true;
    
    /**
     * @var string 
     */
    protected $description = '';

    /**
     * @var array
     */
    protected $commandOptions = [];
    
    /**
     * @param string         $name
     * @param \Closure|null  $handler
     * @param bool           $exitAfterRun
     */
    function __construct($name, \Closure $handler = null, $exitAfterRun = true)
    {
        $this->name = $name;
        $this->handler = $handler;
        $this->exitAfterRun = $exitAfterRun;
    }
    
    /**
     * If application has children - run menu, otherwise run command
     */
    public function run()
    {
        /** @var Helper $helper */
        $helper = $this->getHelper();
        
        $helper->println();
        $helper->println($helper->decorateText($this->name, Helper::COLOR_GREEN));
        if ($this->description) {
            $helper->println($this->description);
        }
        $helper->println();

        if ($this->hasChildren()) {
            $this->runMenu();
        } else {
            $this->runCommand();
        }

        if (false === $this->exitAfterRun && $this->hasParent()) {
            $this->parent->runMenu();
        }
    }
    
    /**
     * Displays children and menu
     */
    public function runMenu()
    {
        /** @var Helper $helper */
        $helper = $this->getHelper();
        
        $helper->println('Available options:');
        $this->renderChildrenMenu();
        $helper->println();

        $choice = $helper->readln();
        if (array_key_exists($choice, $this->children)) {
            $this->children[$choice]->run();
        } elseif ($helper->isEnter($choice)) {
            if ($this->hasParent()) {
                $this->parent->runMenu();
            } else {
                $helper->println("Exit");
                exit;
            }
        } else {
            $helper->println($helper->decorateText(sprintf("Invalid choice: %s", $choice), "Red"));
            $this->runMenu();
        }
    }

    /**
     * Renders children menu
     */
    public function renderChildrenMenu()
    {
        /** @var Helper $helper */
        $helper = $this->getHelper();
        
        foreach ($this->children as $key => $child) {
            $helper->println(sprintf(" * %s: %s", $child->getName(), $helper->decorateText($key, Helper::COLOR_YELLOW)));
        }
        $helper->println(sprintf(" * exit %s", $helper->decorateText("<enter>", 'Yellow')));
    }
    
    /**
     * Runs command
     */
    public function runCommand()
    {
        /** @var Helper $helper */
        $helper = $this->getHelper();
        
        if (null === $this->handler) {
            $helper->println($helper->decorateText('No handler specified', Helper::COLOR_RED));
        }
        $h = $this->handler;
        $h($this);
    }
    
    /**
     * @param Application $app
     * @param string $key
     */
    public function addChild(Application $app, $key = null)
    {
        if (null === $key) {
            $key = count($this->children) + 1;
        }
        $this->children[$key] = $app;
        $app->setParent($this);
    }
    
    /**
     * @return bool
     */
    public function hasChildren()
    {
        return !empty($this->children);
    }
    
    /**
     * @return Application[]
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * @param Application $parent
     */
    function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Application|null
     */
    function getParent()
    {
        return $this->parent;
    }

    /**
     * @return bool
     */
    function hasParent()
    {
        return (null !== $this->parent);
    }
    
    /**
     * @return boolean
     */
    public function isExitAfterRun()
    {
        return $this->exitAfterRun;
    }
    
    
    /**
     * @param boolean $exitAfterRun
     * @return $this
     */
    public function setExitAfterRun($exitAfterRun)
    {
        $this->exitAfterRun = $exitAfterRun;

        return $this;
    }
    
    /**
     * @return Helper
     */
    public function getHelper()
    {
        return Helper::getInstance();
    }
    
    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * 
     * @return $this
     */
    public function setDescription($description)
    {
        $this->description = $description;
        
        return $this;
    }

    /**
     * @return array
     */
    public function getCommandOptions()
    {
        return $this->commandOptions;
    }

    /**
     * @param string        $name
     * @param mixed|null    $default
     * @return mixed
     */
    public function getCommandOption($name, $default = null)
    {
        if (array_key_exists($name, $this->commandOptions)) {
            return $this->commandOptions[$name];
        }
        return $default;
    }

    /**
     * @param string $name
     * @param mixed $value
     *
     * @return $this
     */
    public function setCommandOption($name, $value)
    {
        $this->commandOptions[$name] = $value;

        return $this;
    }

    /**
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
