<?php

namespace Hnk\ConsoleApplicationBundle\CommonTask\Vagrant;

class Ssh extends AbstractVagrantCommand
{
    const BASE_NAME = 'vagrant ssh';

    /**
     * @return null
     */
    public function handler()
    {
        $this->runSimpleVagrantCommand('ssh');
    }
}
