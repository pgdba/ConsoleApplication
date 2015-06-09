<?php

namespace Hnk\ConsoleApplicationBundle\Symfony;

use Hnk\ConsoleApplicationBundle\Helper\RenderHelper;
use Hnk\ConsoleApplicationBundle\Helper\TaskHelper as BaseTaskHelper;

class TaskHelper extends BaseTaskHelper
{
    /**
     * Renders bundle choice
     *
     * @param array     $bundles
     * @param string    $defaultBundle
     * @return array|false
     */
    public function renderBundleChoice(array $bundles, $defaultBundle = null)
    {
        $defaultChoice = null;

        RenderHelper::println('Available bundles:');
        foreach ($bundles as $key => $bundle) {
            RenderHelper::println(sprintf(" * %s: %s", $bundle['name'], RenderHelper::decorateText($key, RenderHelper::COLOR_YELLOW)));
            if ($bundle['name'] == $defaultBundle) {
                $defaultChoice = $key;
            }
        }

        RenderHelper::println();
        $choice = RenderHelper::readln("Choose bundle:", $defaultChoice);
        RenderHelper::println();

        if (array_key_exists($choice, $bundles)) {
            return $bundles[$choice];
        } else {
            return false;
        }
    }

    /**
     * @param  array  $environments
     * @param  string $defaultEnvironment
     *
     * @return bool
     */
    public function renderEnvironmentChoice(array $environments, $defaultEnvironment = null)
    {
        $defaultChoice = null;

        RenderHelper::println('Available environments:');
        foreach ($environments as $key => $env) {
            RenderHelper::println(sprintf(" * %s: %s", $env, RenderHelper::decorateText($key, RenderHelper::COLOR_YELLOW)));
            if ($env == $defaultEnvironment) {
                $defaultChoice = $key;
            }
        }

        RenderHelper::println();
        $choice = $this->renderChoice("Choose environment:", $defaultChoice);
        RenderHelper::println();

        if (array_key_exists($choice, $environments)) {
            return $environments[$choice];
        } else {
            return false;
        }
    }
}
