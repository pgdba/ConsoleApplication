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
    public function getLast()
    {
        return $this->getChoice(0);
    }

    /**
     * @param int $index
     *
     * @return Choice|null
     */
    public function getChoice($index = 0)
    {
        if (isset($this->stack[$index])) {
            return $this->stack[$index];
        }

        return null;
    }

    /**
     * @param int $fromIndex
     * @param int $limit
     *
     * @return Choice[]
     */
    public function getChoices($fromIndex = 0, $limit = 1)
    {
        $choices = array();
        for($i = 0; $i <= $limit; $i++) {
            $choices[$i] = $this->getChoice($fromIndex + $i);
        }

        return $choices;
    }

    /**
     * @return array|Choice[]
     */
    public function getStack()
    {
        return $this->stack;
    }

    /**
     * TODO - sprawdzioc wersje z while
     *
     * @param Choice $choice
     *
     * @return bool
     */
    public function contains(Choice $choice)
    {
        foreach ($this->stack as $key => $stackChoice) {
            if ($stackChoice->equals($choice)) {
                return $key;
            }
        }

        return -1;
    }

    /**
     * @param Choice $choice
     *
     * @return ChoiceStack
     */
    public function addOrRepositionChoice(Choice $choice)
    {
        if (($position = $this->contains($choice)) >= 0) {
            unset($this->stack[$position]);
            $this->stack = array_values($this->stack);
        }
        return $this->addChoice($choice);
    }
}
