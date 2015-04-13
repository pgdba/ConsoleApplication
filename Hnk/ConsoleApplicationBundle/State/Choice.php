<?php

namespace Hnk\ConsoleApplicationBundle\State;
use Hnk\ConsoleApplicationBundle\Command;

/**
 * @author pgdba
 */
class Choice
{
    /**
     * @var Choice|null
     */
    protected $parent;

    /**
     * @var Choice|null
     */
    protected $child;

    /**
     * @var Command
     */
    protected $command;

    /**
     * @return Choice|null
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * TODO - check for self referencing
     *
     * @param  Choice|null $parent
     *
     * @return $this
     */
    public function setParent($parent)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Choice|null
     */
    public function getChild()
    {
        return $this->child;
    }

    /**
     * TODO - check for self referencing
     *
     * @param  Choice|null $child
     *
     * @return $this
     */
    public function setChild($child)
    {
        $this->child = $child;

        return $this;
    }

    /**
     * @return Command
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param  Command $command
     *
     * @return $this
     */
    public function setCommand($command)
    {
        $this->command = $command;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasChild()
    {
        return null !== $this->child;
    }

    /**
     * @return bool
     */
    public function hasParent()
    {
        return null !== $this->parent;
    }

    /**
     * @return bool
     */
    public function hasCommand()
    {
        return null !== $this->command;
    }

    /**
     * @param mixed $choice
     *
     * @return bool
     */
    public function equals($choice) {
        if ($choice === $this) {
            return true;
        }

        if ($this->hasParent()) {
            if (false === $this->getParent()->equals($choice->getParent())) {
                return false;
            }
        } elseif ($choice->hasParent()) {
            return false;
        }

        if ($this->hasChild()) {
            if (false === $this->getChild()->equals($choice->getChild())) {
                return false;
            }
        } elseif ($choice->hasChild()) {
            return false;
        }

        if ($this->hasCommand()) {
            if (false === $this->getCommand()->equals($choice->getCommand())) {
                return false;
            }
        } elseif ($choice->hasCommand()) {
            return false;
        }

        return true;
    }
}
