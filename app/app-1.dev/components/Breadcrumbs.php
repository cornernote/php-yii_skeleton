<?php
Yii::import('bootstrap.widgets.TbBreadcrumbs');
/**
 *
 */
class Breadcrumbs extends TbBreadcrumbs
{
    /**
     * @var array
     */
    public $htmlOptions = array('id' => 'breadcrumbs');
    /**
     * @var bool
     */
    public $homeLink = false;

    /**
     * @return mixed
     */
    public function run()
    {
        if (count($this->links) <= 1)
            return;
        parent::run();
    }


}