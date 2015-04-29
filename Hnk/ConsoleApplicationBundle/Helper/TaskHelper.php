<?php

namespace Hnk\ConsoleApplicationBundle\Helper;

class TaskHelper
{
    /**
     * @var TaskHelper
     */
    protected static $instance = null;

    /**
     * @return TaskHelper
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * Runs command
     * @param string        $command
     * @param string|null   $runDirectory
     * @param boolean       $runForValue
     * @return int
     */
    public function runCommand($command, $runDirectory = null, $runForValue = false)
    {
        if (null !== $runDirectory) {
            RenderHelper::println(sprintf(
                'Running command [%s] in directory: %s',
                RenderHelper::decorateText($command, RenderHelper::COLOR_RED),
                $runDirectory
            ));
            $command = sprintf('cd %s && %s', $runDirectory, $command);
        } else {
            RenderHelper::println(sprintf('Running command [%s]', RenderHelper::decorateText($command, RenderHelper::COLOR_RED)));
        }
        RenderHelper::println();

        $output = '';
        if ($runForValue) {
            ob_start();
        }
        passthru($command, $return);
        if ($runForValue) {
            $output = ob_get_clean();
            echo $output;
        }

        RenderHelper::println();

        return $output;
    }

    /**
     * @param  string $prompt
     * @param  string $default
     *
     * @return string
     */
    public function renderChoice($prompt = 'Choose:', $default = null)
    {
        if ($default !== null) {
            $prompt = sprintf('%s [%s]', $prompt, RenderHelper::decorateText($default, RenderHelper::COLOR_YELLOW));
        }

        RenderHelper::println($prompt);

        $choice = RenderHelper::readln(RenderHelper::COLOR_YELLOW);

        if (RenderHelper::isEnter($choice) && null !== $default) {
            $choice = $default;
        }

        return $choice;
    }

    /**
     * @param  string $prompt
     * @param  bool   $defaultTrue
     *
     * @return bool
     */
    public function renderConfirm($prompt = 'Are you sure?', $defaultTrue = true)
    {
        $default = ($defaultTrue) ? 'y' : 'n';

        $choice = RenderHelper::renderChoice($prompt, $default, RenderHelper::COLOR_GREEN);

        if (in_array($choice, array('y', 'Y', 'yes', 'YES', 'Yes'))) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string        $src
     * @param string|null   $defaultFile
     * @param bool          $askBeforeDir
     *
     * @return string
     *
     * @throws \Exception
     */
    public function renderFileChooser($src, $defaultFile = null, $askBeforeDir = false)
    {
        $defaultChoice = null;

        $files = FileHelper::getFilesInDir($src);
        if ($files) {
            if (count($files) === 1) {
                $defaultChoice = 0;
            }
            RenderHelper::println(sprintf("Files in dir [%s]", $src));
            foreach ($files as $index => $file) {
                $path = $src . '/' . $file;
                if ($defaultFile === $path) {
                    $defaultChoice = $index;
                }
                if (is_dir($path)) {
                    RenderHelper::println(sprintf(" * %s [dir]: %s", $path, RenderHelper::decorateText($index, RenderHelper::COLOR_YELLOW)));
                } else {
                    RenderHelper::println(sprintf(" * %s: %s", $path, RenderHelper::decorateText($index, Helper::COLOR_YELLOW)));
                }
            }

            $choice = $this->renderChoice('Choose file:', $defaultChoice);
            if (array_key_exists($choice, $files)) {
                $path = $src . '/' . $files[$choice];
                if (is_dir($path)) {
                    if ($askBeforeDir) {
                        if ($this->renderConfirm(sprintf('Is this the directory %s?', $path))) {
                            return $path;
                        } else {
                            return $this->renderFileChooser($path, $defaultFile, $askBeforeDir);
                        }
                    } else {
                        return $this->renderFileChooser($path, $defaultFile, $askBeforeDir);
                    }
                } else {
                    return $path;
                }
            } else {
                throw new \Exception('Invalid key');
            }
        } else {
            throw new \Exception(sprintf('No files in dir %s', $src));
        }
    }
}
