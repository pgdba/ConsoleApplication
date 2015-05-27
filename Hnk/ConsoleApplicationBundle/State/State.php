<?php

namespace Hnk\ConsoleApplicationBundle\State;

class State
{
    /**
     * @var ChoiceStack
     */
    protected $choiceStack;

    /**
     * @return ChoiceStack
     */
    public function getChoiceStack()
    {
        return $this->choiceStack;
    }

    /**
     * @param  ChoiceStack $choiceStack
     *
     * @return $this
     */
    public function setChoiceStack($choiceStack)
    {
        $this->choiceStack = $choiceStack;

        return $this;
    }
}
