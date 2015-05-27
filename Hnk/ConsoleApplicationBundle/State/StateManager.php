<?php

namespace Hnk\ConsoleApplicationBundle\State;

class StateManager
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @var StateCacheInterface
     */
    protected $stateCache;

    /**
     * @var State
     */
    protected $state;

    /**
     * @param array $options
     */
    public function __construct($options)
    {
        $this->options = $options;

        $this->stateCache = new StateCache($this->options['saveFile']);

        $this->loadState();
    }

    /**
     * @return State
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     *
     */
    public function saveState()
    {
        $this->stateCache->persist($this->state);
    }

    /**
     *
     */
    protected function loadState()
    {
        if ($this->stateCache->cacheExists()) {
            $this->state = $this->stateCache->load();
        }

        if (!($this->state instanceof State)) {
            $this->state = $this->createState();
        }
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
