<?php

namespace Hnk\ConsoleApplicationBundle\CommonTask\Vagrant;

class RsyncAuto extends AbstractVagrantCommand
{
    const BASE_NAME = 'vagrant rsync-auto';

    /**
     * @return null
     */
    public function handler()
    {
        $this->runSimpleVagrantCommand('rsync-auto');
    }
}
