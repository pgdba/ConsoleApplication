<?php

namespace Hnk\ConsoleApplicationBundle\Menu;

use Hnk\ConsoleApplicationBundle\Exception\UnknownMenuItemException;
use Hnk\ConsoleApplicationBundle\Task\NameableInterface;

interface MenuProviderInterface extends NameableInterface
{
    /**
     * Get choice list for selection
     *
     * @return array   [string => MenuItemInterface]
     */
    public function getItems();

    /**
     * Return selected item
     *
     * @param  string $choice
     *
     * @return MenuItemInterface
     *
     * @throws UnknownMenuItemException
     */
    public function getSelectedItem($choice);

    /**
     * @param  MenuItemInterface $item
     * @param  null              $key
     *
     * @return $this
     */
    public function addItem(MenuItemInterface $item, $key = null);
}
