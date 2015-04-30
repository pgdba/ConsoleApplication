<?php

namespace Hnk\ConsoleApplicationBundle\Symfony;

use Hnk\ConsoleApplicationBundle\Task\CommonTask;
use Hnk\ConsoleApplicationBundle\Task\TaskAbstract;

abstract class SymfonyCommonTask extends CommonTask implements ProjectAwareInterface
{
    /**
     * @var Project
     */
    protected $project;

    /**
     * @var TaskHelper
     */
    protected $helper;
    /**
     * @param string       $name
     * @param Project      $project
     * @param array        $options
     * @param string       $description
     * @param TaskAbstract $parent
     */
    public function __construct($name, Project $project, $options = array(), $description = '', TaskAbstract $parent)
    {
        parent::__construct($name, $options, $description, $parent);

        $this->project = $project;
        $this->helper = TaskHelper::getInstance();
    }

    /**
     * @return Project
     */
    public function getProject()
    {
        return $this->project;
    }

    /**
     * @return TaskHelper
     */
    public function getHelper()
    {
        return $this->helper;
    }
}
