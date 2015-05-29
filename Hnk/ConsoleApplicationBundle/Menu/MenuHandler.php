<?php

namespace Hnk\ConsoleApplicationBundle\Menu;

use Hnk\ConsoleApplicationBundle\Exception\MenuException;
use Hnk\ConsoleApplicationBundle\Exception\NoItemMenuException;
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
     * @throws MenuException
     */
    public function handle(MenuProviderInterface $menuProvider, $defaultChoice = null)
    {
        $items = $menuProvider->getItems();

        if (empty($items)) {
            throw new NoItemMenuException('Menu has no items');
        }

        RenderHelper::println();
        RenderHelper::println('Available options:');

        /** @var MenuItemInterface $item */
        foreach ($items as $key => $item) {
            $options = $item->getMenuOptions();
            if (isset($options['extraSpace'])) {
                RenderHelper::println();
            }
            $label = $item->getName();
            if (isset($options['menuLabel'])) {
                $label = $options['menuLabel'] .$label;
            }

            RenderHelper::println(sprintf(' * %s: %s', $label, RenderHelper::decorateText($key, RenderHelper::COLOR_YELLOW)));
        }
        RenderHelper::println();
        RenderHelper::println(sprintf(' * exit: %s', RenderHelper::decorateText('<enter>', RenderHelper::COLOR_YELLOW)));

        RenderHelper::println();
        $choice = $this->helper->renderChoice('Choose:', $defaultChoice);
        RenderHelper::println();

        if (RenderHelper::isEnter($choice)) {
            return null;
        }

        $selectedItem = $menuProvider->getSelectedItem($choice);

        return $selectedItem;
    }
}
