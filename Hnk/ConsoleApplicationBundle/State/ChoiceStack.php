<?php

namespace Hnk\ConsoleApplicationBundle\State;

/**
 * @author pgdba
 */
class ChoiceStack
{
    const DEFAULT_STACK_LIMIT = 5;

    /**
     * @var int
     */
    protected $stackLimit;

    /**
     * @var Choice[]
     */
    protected $stack;

    /**
     * @param int $stackLimit
     */
    public function __construct($stackLimit = null)
    {
        if (null === $stackLimit) {
            $stackLimit = self::DEFAULT_STACK_LIMIT;
        }

        $this->stackLimit = $stackLimit;
        $this->stack = array();
    }

    /**
     * @param Choice $choice
     *
     * @return $this
     */
    public function addChoice(Choice $choice)
    {
        if ($this->stackLimit === $this->getStackCount()) {
            array_pop($this->stack);
        }
        array_unshift($this->stack, $choice);

        return $this;
    }

    /**
     * @return int
     */
    public function getStackCount()
    {
        return count($this->stack);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return empty($this->stack);
    }

    /**
     * @return Choice|null
     */
    public function getFirst()
    {
        if (!$this->isEmpty()) {
            return $this->stack[0];
        }

        return null;
    }

    /**
     * @return array|Choice[]
     */
    public function getStack()
    {
        return $this->stack;
    }

    /**
     * @param Choice $choice
     *
     * @return bool
     */
    public function contains(Choice $choice)
    {
        foreach ($this->stack as $stackChoice) {
            if ($stackChoice->equals($choice)) {
                return true;
            }
        }

        return false;
    }
}
