<?php
/**
 *
 */
class LookupController extends WebController
{

    /**
     * Access Control
     * @return array
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'view', 'log', 'create', 'update', 'delete'),
                'roles' => array('admin'),
                //'users' => array('*','@','?'), // all, user, guest
            ),
            array('deny', 'users' => array('*')),
        );
    }

    /**
     * Filters
     */
    //public function filters()
    //{
    //    return array(
    //        'inlineFilterName',
    //        array(
    //            'class'=>'path.to.FilterClass',
    //            'propertyName'=>'propertyValue',
    //        ),
    //    );
    //}

    /**
     * Actions
     */
    //public function actions()
    //{
    //    return array(
    //        'action1' => 'path.to.ActionClass',
    //        'action2' => array(
    //            'class' => 'path.to.AnotherActionClass',
    //            'propertyName' => 'propertyValue',
    //        ),
    //    );
    //}

    /**
     * Index
     */
    public function actionIndex()
    {
        $lookup = new Lookup('search');
        if (!empty($_GET['Lookup']))
            $lookup->attributes = $_GET['Lookup'];

        $this->render('index', array(
            'lookup' => $lookup,
        ));
    }

    /**
     * View
     * @param $id
     */
    public function actionView($id)
    {
        /** @var $lookup Lookup */
        $lookup = $this->loadModel($id);

        // check for deleted Lookup
        if ($lookup->deleted) {
            user()->addFlash('THIS RECORD IS DELETED', 'warning');
        }

        $this->render('view', array(
            'lookup' => $lookup,
        ));
    }

    /**
     * Log
     * @param $id
     */
    public function actionLog($id)
    {
        /** @var $lookup Lookup */
        $lookup = $this->loadModel($id);

        $this->render('log', array(
            'lookup' => $lookup,
        ));
    }

    /**
     * Create
     */
    public function actionCreate()
    {
        $lookup = new Lookup('create');

        $this->performAjaxValidation($lookup, 'lookup-form');
        if (isset($_POST['Lookup'])) {
            $lookup->attributes = $_POST['Lookup'];
            if ($lookup->save()) {
                user()->addFlash('Lookup has been created.', 'success');
                $this->redirect(ReturnUrl::getUrl($lookup->getUrl()));
            }
        }
        else {
            if (isset($_GET['Lookup'])) {
                $lookup->attributes = $_GET['Lookup'];
            }
        }

        $this->render('create', array(
            'lookup' => $lookup,
        ));
    }

    /**
     * Update
     * @param $id
     */
    public function actionUpdate($id)
    {
        /** @var $lookup Lookup */
        $lookup = $this->loadModel($id);

        $this->performAjaxValidation($lookup, 'lookup-form');
        if (isset($_POST['Lookup'])) {
            $lookup->attributes = $_POST['Lookup'];
            if ($lookup->save()) {
                user()->addFlash(t('Lookup has been updated'), 'success');
                $this->redirect(ReturnUrl::getUrl($lookup->getUrl()));
            }
            user()->addFlash(t('Lookup could not be updated'), 'warning');
        }

        $this->render('update', array(
            'lookup' => $lookup,
        ));
    }

    /**
     * Delete
     * @param $id
     */
    public function actionDelete($id = null)
    {
        if (!empty($_POST['confirm'])) {
            $ids = sfGrid($id);
            foreach ($ids as $id) {
                $lookup = Lookup::model()->findByPk($id);
                if (!$lookup) {
                    continue;
                }
                $lookup->delete();
                user()->addFlash(sprintf('Lookup %s has been deleted', $lookup->getName()), 'success');
            }
            $this->redirect(ReturnUrl::getUrl(user()->getState('index.lookup', array('/lookup/index'))));
        }

        $this->render('delete', array(
            'id' => $id,
        ));
    }

}
