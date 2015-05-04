<?php

namespace Hnk\ConsoleApplicationBundle\Symfony;

interface ProjectAwareInterface
{
    /**
     * @return Project
     */
    public function getProject();
}
