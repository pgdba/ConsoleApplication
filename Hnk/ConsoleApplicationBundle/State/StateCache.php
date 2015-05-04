<?php

namespace Hnk\ConsoleApplicationBundle\State;

class StateCache implements StateCacheInterface
{
    /**
     * @var bool
     */
    protected $cacheExists;

    /**
     * @var string
     */
    protected $saveFile;

    /**
     * @param  string $saveFile
     *
     * @throws \Exception
     */
    public function __construct($saveFile)
    {
        $this->saveFile = $saveFile;
        $this->checkSaveFile();
    }

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

        if ($this->cacheExists) {
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

    /**
     * @return bool
     */
    public function cacheExists()
    {
        return $this->cacheExists;
    }

    /**
     * @throws \Exception
     */
    protected function checkSaveFile()
    {
        if (!file_exists($this->saveFile)) {
            $h = fopen($this->saveFile, 'w');
            fclose($h);
            $this->cacheExists = false;
        } else {
            if (!is_writable($this->saveFile)) {
                throw new \Exception(sprintf('Save file %s is not writable', $this->saveFile));
            }
            $this->cacheExists = true;
        }
    }
}
