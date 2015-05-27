<?php

namespace Hnk\ConsoleApplicationBundle\Task;

class Task extends TaskAbstract implements RunnableTaskInterface
{
    /**
     * @var \Closure
     */
    protected $handler;

    /**
     * @param string        $name
     * @param \Closure      $handler
     * @param array         $options
     * @param string        $description
     * @param TaskInterface $parent
     */
    public function __construct($name, \Closure $handler, $options = array(), $description = '', TaskInterface $parent = null)
    {
        parent::__construct($name, $options, $description, $parent);
        $this->handler = $handler;
    }

    /**
     * @return null
     */
    public function run()
    {
        if ($this->handler instanceof \Closure) {
            call_user_func_array($this->handler, array($this));
        }
    }
}
