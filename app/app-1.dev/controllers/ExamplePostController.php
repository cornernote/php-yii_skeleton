<?php

/**
 *
 */
class ExamplePostController extends WebController
{

    /**
     * Access Control
     * @return array
     */
    public function accessRules()
    {
        return array(
            array('allow',
                'actions' => array('index', 'view', 'create', 'update', 'delete', 'log'),
                'roles' => array('admin'),
            ),
            array('deny', 'users' => array('*')),
        );
    }

    /**
     * Index
     */
    public function actionIndex()
    {
        $examplePost = new ExamplePost('search');
        if (!empty($_GET['ExamplePost']))
            $examplePost->attributes = $_GET['ExamplePost'];

        $this->render('index', array(
            'examplePost' => $examplePost,
        ));
    }

    /**
     * View
     * @param $id
     */
    public function actionView($id)
    {
        /** @var $examplePost ExamplePost */
        $examplePost = $this->loadModel($id);

        // check for deleted ExamplePost
        if ($examplePost->deleted) {
            user()->addFlash('THIS EXAMPLEPOST IS DELETED', 'warning');
        }

        $this->render('view', array(
            'examplePost' => $examplePost,
        ));
    }

    /**
     * Log
     * @param $id
     */
    public function actionLog($id)
    {
        /** @var $examplePost ExamplePost */
        $examplePost = $this->loadModel($id, 'ExamplePost');

        $this->render('log', array(
            'examplePost' => $examplePost,
        ));
    }

    /**
     * Create
     */
    public function actionCreate()
    {
        $examplePost = new ExamplePost('create');

        $this->performAjaxValidation($examplePost, 'examplePost-form');
        if (isset($_POST['ExamplePost'])) {
            $examplePost->attributes = $_POST['ExamplePost'];
            if ($examplePost->save()) {
                user()->addFlash('ExamplePost has been created.', 'success');
                $this->redirect(ReturnUrl::getUrl($examplePost->getUrl()));
            }
        }
        else {
            if (isset($_GET['ExamplePost'])) {
                $examplePost->attributes = $_GET['ExamplePost'];
            }
        }

        $this->render('create', array(
            'examplePost' => $examplePost,
        ));
    }

    /**
     * Update
     * @param $id
     */
    public function actionUpdate($id)
    {
        /** @var $examplePost ExamplePost */
        $examplePost = $this->loadModel($id);

        $this->performAjaxValidation($examplePost, 'examplePost-form');
        if (isset($_POST['ExamplePost'])) {
            $examplePost->attributes = $_POST['ExamplePost'];
            if ($examplePost->save()) {
                user()->addFlash(t('ExamplePost has been updated'), 'success');
                $this->redirect(ReturnUrl::getUrl($examplePost->getUrl()));
            }
            user()->addFlash(t('ExamplePost could not be updated'), 'warning');
        }

        $this->render('update', array(
            'examplePost' => $examplePost,
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
                $examplePost = ExamplePost::model()->findByPk($id);
                if (!$examplePost) {
                    continue;
                }
                $examplePost->delete();
                user()->addFlash(sprintf('ExamplePost %s has been deleted', $examplePost->getName()), 'success');
            }
            $this->redirect(ReturnUrl::getUrl(user()->getState('index.examplePost', array('/examplePost/index'))));
        }

        $this->render('delete', array(
            'id' => $id,
        ));
    }

}
