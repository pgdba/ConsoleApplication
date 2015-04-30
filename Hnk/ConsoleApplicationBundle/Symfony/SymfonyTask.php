<?php

namespace Hnk\ConsoleApplicationBundle\Symfony;

use Hnk\ConsoleApplicationBundle\Task\Task;
use Hnk\ConsoleApplicationBundle\Task\TaskAbstract;

class SymfonyTask extends Task implements ProjectAwareInterface
{
    /**
     * @var Project
     */
    protected $project;

    /**
     * @param string       $name
     * @param Project      $project
     * @param \Closure     $handler
     * @param array        $options
     * @param string       $description
     * @param TaskAbstract $parent
     */
    public function __construct($name, Project $project, \Closure $handler, $options = array(), $description = '', TaskAbstract $parent = null)
    {
        parent::__construct($name, $handler, $options, $description, $parent);

        $this->project = $project;
    }

    /**
     * @return TaskHelper
     */
    public function getHelper()
    {
        return TaskHelper::getInstance();
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }
}
