<?php
/**
 * In configuration file main.php add this lines of code:
 * 'preload'=>array('globalInit',...),
 *  ...
 * 'components'=>array(
 *   ...
 *   'globalInit'=>array(
 *     'class'=>'GlobalInit',
 *   ),
 */
class GlobalInit extends CApplicationComponent
{
    /**
     *
     */
    public function init()
    {
        parent::init();

        // set an alias to components
        Yii::setPathOfAlias('actions', Yii::app()->getBasePath() . '/components/actions');
        Yii::setPathOfAlias('behaviors', Yii::app()->getBasePath() . '/components/behaviors');
        Yii::setPathOfAlias('validators', Yii::app()->getBasePath() . '/components/validators');
        Yii::setPathOfAlias('widgets', Yii::app()->getBasePath() . '/components/widgets');

        // start the page trail
        PageTrail::model()->findCurrent();
    }
}
