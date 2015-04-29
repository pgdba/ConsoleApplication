<?php

namespace Hnk\ConsoleApplicationBundle\Menu;

use Hnk\ConsoleApplicationBundle\Helper\RenderHelper;
use Hnk\ConsoleApplicationBundle\Helper\TaskHelper;

class MenuHandler
{
    /**
     * @var TaskHelper
     */
    protected $helper;

    /**
     * @param TaskHelper $helper
     */
    public function __construct(TaskHelper $helper)
    {
        $this->helper = $helper;
    }

    /**
     * @param  MenuProviderInterface $menuProvider
     * @param  null                  $defaultChoice
     *
     * @return MenuItemInterface|null
     *
     * @throws \Exception
     */
    public function handle(MenuProviderInterface $menuProvider, $defaultChoice = null)
    {
        $items = $menuProvider->getItems();

        if (empty($items)) {
            throw new \Exception('No items'); // todo
        }

        RenderHelper::println('Available options:');

        /** @var MenuItemInterface $item */
        foreach ($items as $key => $item) {
            RenderHelper::println(sprintf(' * %s: %s', $item->getName(), RenderHelper::decorateText($key, RenderHelper::COLOR_YELLOW)));
        }
        RenderHelper::println(sprintf(' * exit: %s', RenderHelper::decorateText('<enter>', RenderHelper::COLOR_YELLOW)));

        $choice = $this->helper->renderChoice('Choose:', $defaultChoice);

        if (RenderHelper::isEnter($choice)) {
            return null;
        }

        $selectedItem = $menuProvider->getSelectedItem($choice);

        return $selectedItem;
    }
}
