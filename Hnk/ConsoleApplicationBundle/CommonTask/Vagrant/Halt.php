<?php

namespace Hnk\ConsoleApplicationBundle\CommonTask\Vagrant;

class Halt extends AbstractVagrantCommand
{
    const BASE_NAME = 'vagrant halt';

    /**
     * @return null
     */
    public function handler()
    {
        $helper = $this->getHelper();

        $command = sprintf(
            '%s halt%s',
            $this->getCommand('vagrant'),
            $this->getCommandOptionString()
        );

        if ($helper->renderConfirm(sprintf('Do you want to run command %s in directory %s?', $command, $this->projectDirectory))) {
            $helper->runCommand($command, $this->projectDirectory);
        }
    }
}
