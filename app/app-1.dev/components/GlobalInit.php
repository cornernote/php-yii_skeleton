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
        Yii::setPathOfAlias('actions', bp() . '/components/actions');
        Yii::setPathOfAlias('behaviors', bp() . '/components/behaviors');
        Yii::setPathOfAlias('validators', bp() . '/components/validators');

        // start the page trail
        PageTrail::model()->findCurrent();
    }
}
