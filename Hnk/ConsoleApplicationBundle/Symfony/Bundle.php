<?php

namespace Hnk\ConsoleApplicationBundle\Symfony;

use Hnk\ConsoleApplicationBundle\Menu\MenuItemInterface;

class Bundle implements MenuItemInterface
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $path;

    /**
     * @param string $name
     * @param string $path
     */
    public function __construct($name, $path)
    {
        $this->name = $name;
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
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

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param  string $path
     *
     * @return $this
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * @return array
     */
    public function getMenuOptions()
    {
        // TODO: Implement getMenuOptions() method.
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        // TODO: Implement getDescription() method.
    }
}
