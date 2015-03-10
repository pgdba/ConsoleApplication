<?php
/**
 * @author Jakub Rapacz <j.rapacz@tvn.pl>
 */

namespace Hnk\ConsoleApplicationBundle;


class CommonTask
{
    /**
     * @param  string $name
     *
     * @return Command
     */
    public function getChmodCommand($name)
    {
        return new Command($name, function(Command $cmd) {
            $cmd->getHelper()->runCommand(sprintf(
                '%schmod -R %s %s',
                ($cmd->getValue('useSudo', false)) ? 'sudo ' : '',
                $cmd->getValue('mode', 0777),
                $cmd->requireValue('path')
            ), $cmd->requireValue('commandPath'));
        });
    }

    /**
     * @param  string $name
     *
     * @return Command
     */
    public function getRmTask($name)
    {
        return new Command($name, function(Command $cmd) {
            $cmd->getHelper()->runCommand(sprintf(
                '%srm -rf %s',
                ($cmd->getValue('useSudo', false)) ? 'sudo ' : '',
                $cmd->requireValue('path')
            ), $cmd->requireValue('commandPath'));
        });
    }
}
