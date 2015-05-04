<?php

namespace Hnk\ConsoleApplicationBundle\Menu;

interface MenuItemInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return array
     */
    public function getMenuOptions();
}
