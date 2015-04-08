<?php


namespace Hnk\ConsoleApplicationBundle;

/**
 * @author Jakub Rapacz
 */
class Helper
{
    const COLOR_BLACK = 'black';
    const COLOR_BLUE = 'blue';
    const COLOR_GREEN = 'green';
    const COLOR_CYAN = 'cyan';
    const COLOR_RED = 'red';
    const COLOR_PURPLE = 'purple';
    const COLOR_BROWN = 'brown';
    const COLOR_YELLOW = 'yellow';
    const COLOR_WHITE = 'white';
    const COLOR_DEFAULT = 'default';

    protected $colorList = array(
        self::COLOR_BLACK => "\33[30m",
        self::COLOR_BLUE => "\33[34m",
        self::COLOR_GREEN => "\33[32m",
        self::COLOR_CYAN => "\33[36m",
        self::COLOR_RED => "\33[31m",
        self::COLOR_PURPLE => "\33[35m",
        self::COLOR_BROWN => "\33[33m",
        self::COLOR_YELLOW => "\33[33m",
        self::COLOR_WHITE => "\33[37m",
        self::COLOR_DEFAULT => "\33[0m"
    );

    private static $instance = null;
    
    /**
     * @return Helper
     */
    public static function getInstance() 
    {
        if (null === self::$instance) {
            self::$instance = new Helper();
        }
        
        return self::$instance;
    }
    
    /**
     * Returns text with color tags at the endings
     * @param $text
     * @param $color
     * @return string
     */
    public function decorateText($text, $color)
    {
        if (array_key_exists($color, $this->colorList)) {
            $colorTag = $this->colorList[$color];
        } else {
            $colorTag = $this->colorList[self::COLOR_DEFAULT];
        }

        return sprintf('%s%s%s', $colorTag, $text, $this->colorList[self::COLOR_DEFAULT]);
    }

    /**
     * Echoes default color tag
     */
    public function clearColor()
    {
        echo $this->colorList[self::COLOR_DEFAULT];
    }

    /**
     * Echoes test with new line
     * @param string $text
     */
    public function println($text = '')
    {
        echo sprintf('%s%s', $text, PHP_EOL);
    }

    /**
     * @param string $choice
     * @return bool
     */
    public function isEnter($choice)
    {
        return (strlen($choice) === 0);
    }

    /**
     * Reads line from standard input
     * @param string $prompt
     * @param string|null $default
     * @return null|string
     */
    public function readln($prompt = 'Choose:', $default = null)
    {
        if ($default !== null) {
            $prompt = sprintf("%s [%s]", $prompt, $this->decorateText($default, self::COLOR_YELLOW));
        }
        $this->println(sprintf("%s%s", $prompt, $this->colorList[self::COLOR_YELLOW]));

        $choice = trim(fgets(STDIN));

        $this->clearColor();

        if ($this->isEnter($choice) && null !== $default) {
            $choice = $default;
        }

        return $choice;
    }

    /**
     *
     * @param string $prompt
     * @param bool $defaultTrue
     * @return bool
     */
    function renderConfirm($prompt = 'Are you sure?', $defaultTrue = true)
    {
        $default = ($defaultTrue) ? 'y' : 'n';
        $choice = $this->readln($this->decorateText($prompt, 'Green'), $default);

        if (in_array($choice, array('y', 'Y', 'yes', 'YES', 'Yes'))) {
            return true;
        } else {
            return false;
        }
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
            $this->println(sprintf('Running command [%s] in directory: %s', $this->decorateText($command, self::COLOR_RED), $runDirectory));
            $command = sprintf('cd %s && %s', $runDirectory, $command);
        } else {
            $this->println(sprintf('Running command [%s]', $this->decorateText($command, self::COLOR_RED)));
        }
        $this->println();

        $output = '';
        if ($runForValue) {
            ob_start();
        }
        passthru($command, $return);
        if ($runForValue) {
            $output = ob_get_clean();
            echo $output;
        }

        $this->println();
        
        return $output;
    }

    /**
     * @param string $dir
     * @param bool $onlyDirectories
     * @return array
     */
    public function getFilesInDir($dir, $onlyDirectories = false)
    {
        $dir = rtrim($dir, '/') . '/';
        
        $files = array();
        if (is_dir($dir)) {
            $handler = opendir($dir);
            while (false !== ($file = readdir($handler))) {
                if ($file != "." && $file != "..") {
                    if ($onlyDirectories && is_dir($dir . $file)) {
                        $files[] = $file;
                    } elseif (!$onlyDirectories) {
                        $files[] = $file;
                    }
                }
            }
            closedir($handler);
        } else {
            $this->println("not dir: " . $dir);
        }

        return $files;
    }

    /**
     * @param string $text
     */
    public function printError($text)
    {
        return $this->println($this->decorateText($text, self::COLOR_RED));
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

        $files = $this->getFilesInDir($src);
        if ($files) {
            if (count($files) === 1) {
                $defaultChoice = 0;
            }
            $this->println(sprintf("Files in dir [%s]", $src));
            foreach ($files as $index => $file) {
                $path = $src . '/' . $file;
                if ($defaultFile === $path) {
                    $defaultChoice = $index;
                }
                if (is_dir($path)) {
                    $this->println(sprintf(" * %s [dir]: %s", $path, $this->decorateText($index, Helper::COLOR_YELLOW)));
                } else {
                    $this->println(sprintf(" * %s: %s", $path, $this->decorateText($index, Helper::COLOR_YELLOW)));
                }
            }

            $choice = $this->readln('Choose file:', $defaultChoice);
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
