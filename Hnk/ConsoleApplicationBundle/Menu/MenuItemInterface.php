<?php

namespace Hnk\ConsoleApplicationBundle\Menu;

use Hnk\ConsoleApplicationBundle\Task\NameableInterface;

interface MenuItemInterface extends NameableInterface
{
    /**
     * @return array
     */
    public function getMenuOptions();
}
