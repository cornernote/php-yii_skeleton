<?php
class JavaScriptWidget extends CWidget
{
    public $id;

    public function init()
    {
        ob_start();
    }

    public function run()
    {
        // get id
        if (!$this->id) {
            $this->id = 'script-' . uniqid();
        }

        // get contents
        $contents = ob_get_clean();
        $contents = str_replace(array('<script>', '<script type="text/javascript">', '</script>'), '', $contents);

        // register the js script
        cs()->registerScript($this->id, $contents);
    }
}