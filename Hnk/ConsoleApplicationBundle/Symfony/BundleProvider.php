<?php

namespace Hnk\ConsoleApplicationBundle\Symfony;

use Hnk\ConsoleApplicationBundle\Exception\NotADirectoryException;
use Hnk\ConsoleApplicationBundle\Exception\UnknownMenuItemException;
use Hnk\ConsoleApplicationBundle\Helper\FileHelper;
use Hnk\ConsoleApplicationBundle\Menu\MenuItemInterface;
use Hnk\ConsoleApplicationBundle\Menu\MenuProviderInterface;

class BundleProvider implements MenuProviderInterface
{
    /**
     * @var string
     */
    protected $srcPath;

    /**
     * @var Bundle[]
     */
    protected $bundles = array();

    /**
     * @var bool
     */
    protected $isLoaded = false;

    /**
     * @param string $srcPath
     */
    public function __construct($srcPath)
    {
        $this->srcPath = $srcPath;
    }

    /**
     * @return array
     */
    public function getBundles()
    {
        if (!$this->isLoaded) {
            $this->recurrentBundleFinder($this->srcPath);
            $this->isLoaded = true;
        }

        return $this->bundles;
    }

    /**
     * Get choice list for selection
     *
     * @return array   [string => MenuItemInterface]
     */
    public function getItems()
    {
        return $this->getBundles();
    }

    /**
     * Return selected item
     *
     * @param  string $choice
     *
     * @return MenuItemInterface
     *
     * @throws UnknownMenuItemException
     */
    public function getSelectedItem($choice)
    {
        if (!array_key_exists($choice, $this->bundles)) {
            throw new UnknownMenuItemException(sprintf('No bundle with key %s', $choice));
        }

        return $this->bundles[$choice];
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'Bundles';
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return '';
    }

    /**
     * Looks for Symfony bundles in $srcPath
     *
     * @param string $srcDir
     * @param string $path
     * @param int    $depth
     * @param int    $maxDepth
     *
     * @throws NotADirectoryException
     */
    protected function recurrentBundleFinder($srcDir, $path = '', $depth = 1, $maxDepth = 4)
    {
        $srcDir = rtrim($srcDir, '/') . '/';

        if ($depth > $maxDepth) {
            return;
        }

        if (!$path) {
            $files = FileHelper::getFilesInDir($srcDir, true);
            if ($files) {
                foreach ($files as $file) {
                    $this->recurrentBundleFinder($srcDir, $file . '/', $depth + 1, $maxDepth);
                }
            } else {
                return;
            }
        } else {
            $bundleName = str_replace('/', '', $path);

            if (file_exists($srcDir . $path . $bundleName . '.php')) { // TODO - create more sophisticated hack
                $key = count($this->bundles) + 1;
                $bundles[$key] = new Bundle($bundleName, $path);
                return;
            } else {
                $files = FileHelper::getFilesInDir($srcDir . $path, true);
                if ($files) {
                    foreach ($files as $file) {
                        $this->recurrentBundleFinder($srcDir, $path . $file . '/', $depth + 1, $maxDepth);
                    }
                }
            }
        }
        return;
    }

    /**
     * @param  MenuItemInterface $item
     * @param  null              $key
     *
     * @return $this
     *
     * @throws \Exception
     */
    public function addItem(MenuItemInterface $item, $key = null)
    {
        if ($item instanceof Bundle) {
            $this->bundles[$key] = $item;
        } else {
            throw \Exception(sprintf('Invalid item type: %s', get_class($item)));
        }

        return $this;
    }
}
