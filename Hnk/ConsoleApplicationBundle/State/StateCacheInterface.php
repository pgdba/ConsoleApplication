<?php

namespace Hnk\ConsoleApplicationBundle\State;

interface StateCacheInterface
{
    /**
     * @param State $state
     */
    public function persist(State $state);

    /**
     * @return State
     */
    public function load();
}
