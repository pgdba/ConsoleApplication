<?php

namespace Hnk\ConsoleApplicationBundle\CommonTask\Vagrant;

class Up extends AbstractVagrantCommand
{
    const BASE_NAME = 'vagrant up';

    /**
     * @return null
     */
    public function handler()
    {
        $helper = $this->getHelper();

        $command = sprintf(
            '%s up%s',
            $this->getCommand('vagrant'),
            $this->getCommandOptionString()
        );

        if ($helper->renderConfirm(sprintf('Do you want to run command %s in directory %s?', $command, $this->projectDirectory))) {
            $helper->runCommand($command, $this->projectDirectory);
        }
    }

    /**
     * Adds provision option
     */
    public function provision()
    {
        if (!in_array('--provision', $this->commandOptions)) {
            $this->commandOptions[] = '--provision';
        }
    }
}
