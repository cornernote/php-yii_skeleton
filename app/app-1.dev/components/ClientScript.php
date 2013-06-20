<?php

/**
 *
 */
class ClientScript extends CClientScript
{

    /**
     * @var array the registered CSS files (CSS URL=>media type).
     * @since 1.0.4
     */
    protected $cssFiles = array();
    /**
     * @var array
     */
    protected $cssFilesOrder = array();

    /**
     * @var array
     */
    protected $css = array();
    /**
     * @var array
     */
    protected $cssOrder = array();
    /**
     * @var array the registered JavaScript files (position, key => URL)
     * @since 1.0.4
     */
    protected $scriptFiles = array();
    /**
     * @var array
     */
    protected $scriptFilesOrder = array();
    /**
     * @var array the registered JavaScript code blocks (position, key => code)
     * @since 1.0.5
     */
    protected $scripts = array();
    /**
     * @var array
     */
    protected $scriptsOrder = array();

    /**
     * Registers a CSS file
     * @param string $url URL of the CSS file
     * @param string $media media that the CSS file should be applied to. If empty, it means all media types.
     * @param array $options
     * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
     */
    public function registerCssFile($url, $media = '', $options = array())
    {
        $options = array_merge(array(
            'order' => 0,
        ), $options);
        $this->hasScripts = true;
        $this->cssFiles[$url] = $media;
        $this->cssFilesOrder[$url] = $options['order'];
        $params = func_get_args();
        $this->recordCachingAction('clientScript', 'registerCssFile', $params);
        return $this;
    }

    /**
     * @param string $id
     * @param string $css
     * @param string $media
     * @param array $options
     * @return \CClientScript
     */
    public function registerCss($id, $css, $media = '', $options = array())
    {
        $options = array_merge(array(
            'order' => 0,
        ), $options);
        $this->hasScripts = true;
        $this->css[$id] = array($css, $media);
        $this->cssOrder[$id] = $options['order'];
        $params = func_get_args();
        $this->recordCachingAction('clientScript', 'registerCssFile', $params);
        return $this;
    }

    /**
     * Renders the registered scripts.
     * This method is called in {@link CController::render} when it finishes
     * rendering content. CClientScript thus gets a chance to insert script tags
     * at <code>head</code> and <code>body</code> sections in the HTML output.
     * @param string $output the existing output that needs to be inserted with script tags
     */
    public function render(&$output)
    {
        if (!$this->hasScripts)
            return;

        $this->renderCoreScripts();

        if (!empty($this->scriptMap))
            $this->remapScripts();

        $this->unifyScripts();
        $this->reorderScripts();

        $this->renderHead($output);
        if ($this->enableJavaScript) {
            $this->renderBodyBegin($output);
            $this->renderBodyEnd($output);
        }
    }

    /**
     *
     */
    public function reorderScripts()
    {
        // reorder the cssFiles
        if (!empty($this->cssFilesOrder)) {
            asort($this->cssFilesOrder);
            $newCssFiles = array();
            foreach ($this->cssFilesOrder as $url => $order) {
                $newCssFiles[$url] = $this->cssFiles[$url];
            }
            $this->cssFiles = $newCssFiles;
        }
        // reorder css
        if (!empty($this->cssOrder)) {
            asort($this->cssOrder);
            $newCss = array();
            foreach ($this->cssOrder as $id => $order) {
                $newCss[$id] = $this->css[$id];
            }
            $this->css = $newCss;
        }
        // reorder the scriptFiles
        if (!empty($this->scriptFilesOrder[self::POS_END])) {
            asort($this->scriptFilesOrder[self::POS_END]);
            $newScriptFiles = array();
            foreach ($this->scriptFilesOrder[self::POS_END] as $url => $order) {
                $newScriptFiles[$url] = $url;
            }
            $this->scriptFiles[self::POS_END] = $newScriptFiles;
        }
        // reorder the script
        if (!empty($this->scriptsOrder[self::POS_READY])) {
            asort($this->scriptsOrder[self::POS_READY]);
            $newScript = array();
            foreach ($this->scriptsOrder[self::POS_READY] as $id => $order) {
                $newScript[$id] = $this->scripts[self::POS_READY][$id];
            }
            $this->scripts[self::POS_READY] = $newScript;
        }
    }


    /**
     * Registers a javascript file.
     * @param string $url URL of the javascript file
     * @param integer $position the position of the JavaScript code. Valid values include the following:
     * <ul>
     * <li>CClientScript::POS_HEAD : the script is inserted in the head section right before the title element.</li>
     * <li>CClientScript::POS_BEGIN : the script is inserted at the beginning of the body section.</li>
     * <li>CClientScript::POS_END : the script is inserted at the end of the body section.</li>
     * </ul>
     * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
     */
    public function registerScriptFile($url, $position = self::POS_HEAD)
    {
        // do not load these scripts on ajax
        if (isAjax() && strpos($url, 'jquery-ui.min.js') !== false) {
            return;
        }
        parent::registerScriptFile($url, $position);
    }

    /**
     * Registers a script package that is listed in {@link packages}.
     * @param string $name the name of the script package.
     * @return CClientScript the CClientScript object itself (to support method chaining, available since version 1.1.5).
     * @see renderCoreScript
     */
    public function registerCoreScript($name)
    {
        // do not load these scripts on ajax
        if (isAjax() && in_array($name, array('jquery', 'yiiactiveform'))) {
            return;
        }
        parent::registerCoreScript($name);
    }

}
