<?php

namespace Hnk\ConsoleApplicationBundle\State;

interface StateManagerInterface
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
