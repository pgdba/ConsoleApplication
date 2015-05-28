<?php

namespace Hnk\ConsoleApplicationBundle\CommonTask\Vagrant;

class Provision extends AbstractVagrantCommand
{
    const BASE_NAME = 'vagrant provision';

    /**
     * @return null
     */
    public function handler()
    {
        $this->runSimpleVagrantCommand('provision');
    }
}
