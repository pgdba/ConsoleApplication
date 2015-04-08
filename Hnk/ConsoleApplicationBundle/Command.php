<?php

namespace Hnk\ConsoleApplicationBundle;

/**
 * @author pgdba
 */
class Command
{
    /**
     * @var string 
     */
    protected $name;
    
    /**
     * @var array
     */
    protected $children = array();
    
    /**
     * @var \Closure|null
     */
    protected $handler = null;

    /**
     * @var Command|null
     */
    protected $parent = null;

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @var array
     */
    private $defaultSettings = array(
        'exitAfterRun' => true,
    );

    /**
     * @var string 
     */
    protected $description = '';

    /**
     * @var array
     */
    protected $values = array();
    
    /**
     * @param string        $name
     * @param \Closure|null $handler
     * @param array         $settings
     */
    function __construct($name, \Closure $handler = null, $settings = array())
    {
        $this->name = $name;
        $this->handler = $handler;
        $this->settings = array_merge($this->defaultSettings, $settings);
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

        if (false === $this->getSetting('exitAfterRun', false) && $this->hasParent()) {
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
     * @param Command $app
     * @param string $key
     */
    public function addChild(Command $app, $key = null)
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
     * @return Command[]
     */
    public function getChildren()
    {
        return $this->children;
    }
    
    /**
     * @param Command $parent
     */
    function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return Command|null
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
     * @param  string     $name
     * @param  mixed|null $default
     *
     * @return mixed
     */
    public function getSetting($name, $default = null)
    {
        if (!array_key_exists($name, $this->settings)) {
            return $default;
        }

        return $this->settings[$name];
    }

    /**
     * @param  string $name
     * @param  mixed  $value
     *
     * @return $this
     */
    public function setSetting($name, $value)
    {
        $this->settings[$name] = $value;

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
     * @param  string $description
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
    public function getValues()
    {
        return $this->values;
    }

    /**
     * @param  string     $name
     * @param  mixed|null $default
     *
     * @return mixed
     */
    public function getValue($name, $default = null)
    {
        if (array_key_exists($name, $this->values)) {
            return $this->values[$name];
        }

        return $default;
    }

    /**
     * @param  string $name
     *
     * @return mixed
     *
     * @throws \Exception
     */
    public function requireValue($name)
    {
        if (array_key_exists($name, $this->values)) {
            return $this->values[$name];
        }

        throw new \Exception(sprintf('Value %s is required by this command', $name));
    }

    /**
     * @param  string $name
     * @param  mixed $value
     *
     * @return $this
     */
    public function setValue($name, $value)
    {
        $this->values[$name] = $value;

        return $this;
    }

    /**
     * @param  string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}
