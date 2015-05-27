<?php

namespace Hnk\ConsoleApplicationBundle\State;

use Hnk\ConsoleApplicationBundle\Task\TaskAbstract;
use Hnk\ConsoleApplicationBundle\Task\TaskIdentifier;

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
     * @var TaskIdentifier
     */
    protected $task;

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
//        $this->parent->setChild($this);

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
     * @return Choice
     */
    public function getLastChild()
    {
        if ($this->hasChild()) {
            return $this->child->getLastChild();
        }

        return $this;
    }

    public function removeLastChoiceTask()
    {
        if ($this->hasChild()) {
            return $this->child->removeLastChoiceTask();
        }

        $this->task = null;

        return $this;
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
     * @return TaskIdentifier
     */
    public function getTask()
    {
        return $this->task;
    }

    /**
     * @param  TaskIdentifier $task
     *
     * @return $this
     */
    public function setTask(TaskIdentifier $task)
    {
        $this->task = $task;

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
    public function hasTask()
    {
        return null !== $this->task;
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

        if ($this->hasTask()) {
            if (false === $this->getTask()->equals($choice->getTask())) {
                return false;
            }
        } elseif ($choice->hasTask()) {
            return false;
        }

        return true;
    }

    /**
     * @return string
     */
    public function getChoiceName()
    {
        $name = $this->task->getName();

        if ($this->hasChild()) {
            $name .= ' > ' . $this->child->getChoiceName();
        }

        return $name;
    }

    /**
     * @return TaskAbstract
     */
    public function getChoiceTask()
    {
        if ($this->hasChild()) {
            return $this->child->getChoiceTask();
        }

        return $this->task;
    }

    public function getPreviousTaskId($taskId = null)
    {
        if ($this->hasChild()) {
            return $this->child->getPreviousTaskId($this->task->getId());
        }

        return $taskId;
    }
}
