<?php

namespace Hnk\ConsoleApplicationBundle\State;

class StateManager implements StateManagerInterface
{
    /**
     * @var string
     */
    protected $saveFile;

    /**
     * @param State $state
     */
    public function persist(State $state)
    {
        try {
            file_put_contents($this->saveFile, serialize($state));
        } catch(\Exception $e) {
            ed($e, 'EXCEPTION');
        }
    }

    /**
     * @return State|null
     */
    public function load()
    {
        $state = null;

        if (file_exists($this->saveFile)) {
            try {
                $serialized = file_get_contents($this->saveFile);

                $state = unserialize($serialized);
            } catch(\Exception $e) {
                ed($e, 'EXCEPTION');
            }
        }

        return $state;
    }

    /**
     * @param  string $saveFile
     *
     * @return $this
     */
    public function setSaveFile($saveFile)
    {
        $this->saveFile = $saveFile;

        return $this;
    }
}
