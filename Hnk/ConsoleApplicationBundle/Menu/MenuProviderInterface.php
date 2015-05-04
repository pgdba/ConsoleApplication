<?php

namespace Hnk\ConsoleApplicationBundle\Menu;

use Hnk\ConsoleApplicationBundle\Exception\UnknownMenuItemException;

interface MenuProviderInterface
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
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @param  MenuItemInterface $item
     * @param  null              $key
     *
     * @return $this
     */
    public function addItem(MenuItemInterface $item, $key = null);
}
