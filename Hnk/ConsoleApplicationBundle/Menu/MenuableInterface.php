<?php

namespace Hnk\ConsoleApplicationBundle\Menu;

interface MenuableInterface
{
    /**
     * Shows choice list for selection
     */
    public function showMenu();

    /**
     * @param string $choice
     */
    public function runSelectedAction($choice);

    /**
     * Return to previous page / upper level
     */
    public function goBack();
}
