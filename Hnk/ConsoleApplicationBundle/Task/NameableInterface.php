<?php

namespace Hnk\ConsoleApplicationBundle\Task;

interface NameableInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getDescription();
}
