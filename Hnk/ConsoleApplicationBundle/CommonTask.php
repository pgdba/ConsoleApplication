<?php
/**
 * @author Jakub Rapacz <j.rapacz@tvn.pl>
 */

namespace Hnk\ConsoleApplicationBundle;


class CommonTask
{
    /**
     * @param  string $name
     * @param  string $path
     * @param  string $commandPath
     * @param  int    $mode
     * @param  bool   $useSudo
     *
     * @return Application
     */
    public function getChmodTask($name, $path, $commandPath, $mode = 0777, $useSudo = true)
    {
        return new Application($name, function(Application $app) use($path, $commandPath, $mode, $useSudo) {
            $app->getHelper()->runCommand(sprintf(
                '%schmod -R %s %s',
                ($useSudo) ? 'sudo ' : '',
                $mode,
                $path
            ), $commandPath);
        });
    }

    /**
     * @param  string $name
     * @param  string $path
     * @param  string $commandPath
     * @param  bool $useSudo
     *
     * @return Application
     */
    public function getRmTask($name, $path, $commandPath, $useSudo = true)
    {
        return new Application($name, function(Application $app) use($path, $commandPath, $useSudo) {
            $app->getHelper()->runCommand(sprintf(
                '%srm -rf %s',
                ($useSudo) ? 'sudo ' : '',
                $path
            ), $commandPath);
        });
    }
}
