<?php
/**
 * Controller is the customized base controller class.
 * All controller classes for this application should extend from this base class.
 */
class Controller extends CController
{
    /**
     * @var PageTrail
     */
    protected $pageTrail;

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * This method is invoked right before an action is to be executed (after all possible filters.)
     * @param CAction $action the action to be executed.
     * @return boolean whether the action should be executed.
     */
    protected function beforeAction($action)
    {
        // log the page request
        $this->pageTrail = new PageTrail();
        $this->pageTrail->recordPageTrail();

        // handle errors
        app()->onEndRequest = array($this->pageTrail, 'updatePageTrail');
        app()->onError = array($this, 'logError');

        return parent::beforeAction($action);
    }

    /**
     * @param $event CErrorEvent
     */
    public function logError($event)
    {
        $errorHandler = new ErrorHandler();
        $errorHandler->LogError($event);
    }

    /**
     * @param $string
     * @param bool $return
     * @return mixed
     */
    public function renderString($string, $return = false)
    {
        //if $this->getLayoutFile($this->layout) not false use layout file
        if (($layoutFile = $this->getLayoutFile($this->layout)) !== false)
            $string = $this->renderFile($layoutFile, array('content' => $string), true);
        $string = $this->processOutput($string);
        if ($return)
            return $string;
        else
            echo $string;
    }

}
