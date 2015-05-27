<?php

namespace Hnk\ConsoleApplicationBundle\Helper;

class RenderHelper
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

    protected static $colorTags = array(
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

    /**
     * Echoes test with new line
     *
     * @param string $text
     * @param string $color
     *
     * @return null
     */
    public static function println($text = '', $color = null)
    {
        if (null !== $color) {
            echo self::getColorTag($color);
        }
        echo $text . PHP_EOL;

        if (null !== $color) {
            echo self::getColorTag($color);
        }
    }

    /**
     * @param  string $color
     *
     * @return string
     */
    public static function readln($color = null)
    {
        if (null !== $color) {
            self::setColor($color);
        }

        $line = trim(fgets(STDIN));

        if (null !== $color) {
            self::$colorTags[self::COLOR_DEFAULT];
        }

        return $line;
    }

    /**
     * @param string $color
     *
     * @return null
     */
    public static function setColor($color)
    {
        echo self::getColorTag($color);
    }

    /**
     * @return null
     */
    public static function clearColor()
    {
        self::setColor(self::COLOR_DEFAULT);
    }

    /**
     * @param  string $text
     * @param  string $color
     *
     * @return string
     */
    public static function decorateText($text, $color)
    {
        $colorTag = self::getColorTag($color);

        return sprintf('%s%s%s', $colorTag, $text, self::$colorTags[self::COLOR_DEFAULT]);
    }

    /**
     * @param  string $choice
     *
     * @return bool
     */
    public static function isEnter($choice)
    {
        return (strlen($choice) === 0);
    }

    /**
     * @param  string $color
     *
     * @return string
     */
    public static function getColorTag($color)
    {
        return self::$colorTags[(array_key_exists($color, self::$colorTags) ? $color : self::COLOR_DEFAULT)];
    }

    /**
     * @param  string $text
     *
     * @return null
     */
    public static function printError($text)
    {
        self::println($text, self::COLOR_RED);
    }
}
