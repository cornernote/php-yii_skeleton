<?php

class FontAwesome extends CWidget
{
    // function to init the widget
    public function init()
    {
        $this->publishAssets();
    }

    // function to publish and register assets on page
    public function publishAssets()
    {
        $basePath = vp() . DS . 'font-awesome' . DS . 'font-awesome-3.2.1';
        $baseUrl = Yii::app()->assetManager->publish($basePath, false, 1, YII_DEBUG);
        Yii::app()->clientScript->registerCssFile($baseUrl . '/css/font-awesome.min.css', 'screen, projection');
    }
}