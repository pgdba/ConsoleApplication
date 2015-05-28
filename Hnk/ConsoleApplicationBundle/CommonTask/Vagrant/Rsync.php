<?php

namespace Hnk\ConsoleApplicationBundle\CommonTask\Vagrant;

class Rsync extends AbstractVagrantCommand
{
    const BASE_NAME = 'vagrant rsync';

    /**
     * @return null
     */
    public function handler()
    {
        $this->runSimpleVagrantCommand('rsync');
    }
}
