<?php

// Licensed under these terms:
// http://www.opensource.org/licenses/bsd-license.php
// (c) David Renty

require_once(bp() . '/../../vendors/mustache/Mustache.php');

/**
 *
 */
class MustacheCom
{
    /**
     * @var Mustache
     */
    public static $MustacheV;

    static public function setVendor()
    {
        if (!self::$MustacheV) {
            self::$MustacheV = new Mustache;
        }
    }

    /**
     * Render the given template and view object.
     *
     * Defaults to the template and view passed to the class constructor unless a new one is provided.
     * Optionally, pass an associative array of partials as well.
     *
     * @param string $templateName
     * @param string $type
     * @param mixed $view (default: null)
     * @param null $partialNames     *
     * @return string Rendered Mustache template.
     */
    static public function render($templateName, $type, $view = null, $partialNames = null)
    {
        self::setVendor();

        return $output;
    }

}
