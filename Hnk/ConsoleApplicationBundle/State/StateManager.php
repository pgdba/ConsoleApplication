<?php

namespace Hnk\ConsoleApplicationBundle\State;

class StateManager
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var StateCache
     */
    protected $stateCache;

    /**
     * @param array $options
     */
    public function __construct($options)
    {
        $this->options = $options;

        $this->stateCache = new StateCache($this->options['saveFile']);
    }

    /**
     * @return State
     */
    public function getState()
    {
        $state = null;

        if ($this->stateCache->cacheExists()) {
            $state = $this->stateCache->load();
        }

        if (!($state instanceof State)) {
            $state = $this->createState();
        }

        return $state;
    }

    /**
     * @param State $state
     */
    public function saveState(State $state)
    {
        $this->stateCache->persist($state);
    }

    /**
     * @return State
     */
    protected function createState()
    {
        $stackLimit = (isset($this->options['stackLimit'])) ? $this->options['stackLimit'] : null;

        $state = new State();
        $state->setChoiceStack(new ChoiceStack($stackLimit));

        return $state;
    }
}
