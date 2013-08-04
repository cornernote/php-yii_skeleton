<?php

/**
 * PageTrailController
 *
 * @package app.controller
 * @author Brett O'Donnell <brett@mrphp.com.au>
 */
class PageTrailController extends WebController
{

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'view', 'preserve', 'unPreserve'),
                'roles' => array('admin'),
            ),
            array('deny', 'users' => array('*')),
        );
    }

    /**
     * Lists all PageTrails.
     */
    public function actionIndex()
    {
        $pageTrail = new PageTrail('search');
        $urlManager = app()->getUrlManager();
        $urlManager->setUrlFormat('get');
        $this->render('index', array(
            'pageTrail' => $pageTrail,
        ));
    }


    /**
     * Displays a particular PageTrail.
     * @param integer $id the ID of the PageTrail to be displayed
     */
    public function actionView($id)
    {
        $pageTrail = $this->loadModel($id);
        $this->render('view', array(
            'pageTrail' => $pageTrail,
        ));
    }

    /**
     * Preserves a particular PageTrail.
     * @param integer $id the ID of the PageTrail to be displayed
     * @param int $status
     * @return void
     */
    public function actionPreserve($id, $status = 1)
    {
        $id = (int)$id;
        $status = (int)$status;
        $pageTrail = $this->loadModel($id);
        $sql = "UPDATE " . PageTrail::model()->tableName() . " SET preserve = $status WHERE id = $id";
        app()->db->createCommand($sql)->execute();
        $this->redirect($pageTrail->getUrl(), true);
    }

}
