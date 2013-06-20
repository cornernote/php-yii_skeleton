<?php

/**
 *
 * @package app.controller
 * @author Brett O'Donnell <brett@mrphp.com.au>
 */
class UserController extends WebController
{

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        $staffUpdate = 'dummy';
        if (user()->user) {
            $staffUpdate = $this->getAuthorizedUpdateAction();
        }

        return array(
            array('allow',
                'actions' => array('login', 'recover'),
                'users' => array('*'),
            ),
            array('allow',
                'actions' => array('logout'),
                'users' => array('@'),
            ),
            array('allow',
                'actions' => array('index', 'view', 'create', $staffUpdate, 'log', 'autoCompleteLookup', 'delete'),
                'roles' => array('staff'),
            ),
            array('allow',
                'actions' => array('changeUser', 'undelete'),
                'roles' => array('admin'),
            ),
            array('deny',
                'users' => array('*'),
            ),
        );
    }

    /**
     * Displays the login page
     */
    public function actionLogin()
    {
        // redirect if the user is already logged in
        if (user()->id) {
            $this->redirect(Yii::app()->homeUrl);
        }

        // allow a secure link to auto-login
        $valid = SecureLink::validate($_GET, array('email', 'hash'));
        if ($valid) {
            $identity = new UserIdentity($_GET['email'], $_GET['hash']);
            if ($identity->authenticateSecureLink()) {
                user()->login($identity, $duration = 0);
                $this->redirect(Yii::app()->homeUrl);
            }
        }

        // enable recaptcha after 3 attempts
        $attempts = Yii::app()->cache->get("login.attempt.{$_SERVER['REMOTE_ADDR']}");
        if (!$attempts)
            $attempts = 0;
        $scenario = ($attempts >= 3) ? 'recaptcha' : '';

        $user = new UserLogin($scenario);

        // collect user input data
        if (isset($_POST['UserLogin'])) {
            $error = false;

            $user->attributes = $_POST['UserLogin'];
            // validate user input and redirect to the previous page if valid
            if (!$user->validate()) {
                $error = true;
            }

            if (!$error && !$user->login()) {
                if ($_POST['UserLogin']['username'] != 'lcd') {
                    Yii::app()->cache->set("login.attempt.{$_SERVER['REMOTE_ADDR']}", ++$attempts);
                }
                $error = true;
            }

            if (!$error) {
                Yii::app()->cache->delete("login.attempt.{$_SERVER['REMOTE_ADDR']}");
                $this->redirect(ReturnUrl::getUrl());
            }
        }

        // display the login form
        $this->layout = 'narrow';
        $this->render('login', array(
            'user' => $user,
            'recaptcha' => ($attempts >= 3 && Setting::item('app', 'recaptcha')) ? true : false,
        ));
    }

    /**
     * Displays the recover page
     */
    public function actionRecover()
    {
        // redirect if the user is already logged in
        if (user()->id) {
            $this->redirect(Yii::app()->homeUrl);
        }

        // user has clicked the email link
        if (isset($_GET['email']) && isset($_GET['hash']) && isset($_GET['key'])) {
            $userPassword = new UserPassword('recover');
            $this->performAjaxValidation($userPassword, 'password-form');

            $user = User::model()->findByAttributes(array('email' => $_GET['email'], 'password' => $_GET['hash']));
            $valid = SecureLink::validate($_GET, array('email', 'hash'));

            if ($user && $valid) {
                if (isset($_POST['UserPassword'])) {
                    $userPassword->attributes = $_POST['UserPassword'];
                    if ($userPassword->validate()) {

                        $user->confirm_password = $userPassword->password;
                        $user->password = $userPassword->password;
                        if (!$user->save(false)) {
                            user()->addFlash(t('Your password could not be saved.'), 'error');

                        }

                        $identity = new UserIdentity($user->email, $userPassword->confirm_password);
                        if ($identity->authenticate()) {
                            user()->login($identity);
                        }

                        Log::model()->add('password has been saved and user logged in', array(
                            'model' => 'PasswordRecover',
                            'model_id' => 0,
                            'details' => array(
                                'user_id' => $user->id,
                            ),
                        ));
                        user()->addFlash(t('Your password has been saved and you have been logged in.'), 'success');
                        $this->redirect(Yii::app()->homeUrl);
                    }
                    else {
                        Log::model()->add('password could not be saved ', array(
                            'model' => 'PasswordRecover',
                            'model_id' => 1,
                            'details' => array(
                                'user_id' => $user->id,
                            ),
                        ));
                        user()->addFlash(t('Your password could not be saved.'), 'warning');
                    }
                }
                $this->render('password', array('user' => $userPassword));

            }
            else {
                Log::model()->add('password could not be saved due to an invalid key', array(
                    'model' => 'PasswordRecover',
                    'model_id' => 2,
                    'details' => array(
                        'user_id' => user()->id,
                    ),
                ));
                user()->addFlash(t('Invalid key.'), 'warning');
                $this->redirect(array('/user/recover'));
            }
        }

        // user is requesting recover email
        else {

            // enable recaptcha after 3 attempts
            $attempts = Yii::app()->cache->get("recover.attempt.{$_SERVER['REMOTE_ADDR']}");
            if (!$attempts)
                $attempts = 0;
            $scenario = ($attempts >= 3) ? 'recaptcha' : '';

            $userRecover = new UserRecover($scenario);
            $this->performAjaxValidation($userRecover, 'recover-form');

            // collect user input data
            if (isset($_POST['UserRecover'])) {
                $userRecover->attributes = $_POST['UserRecover'];

                if ($userRecover->validate()) {
                    $user = User::model()->findbyPk($userRecover->user_id);
                    email()->sendRecoverPasswordEmail($user);
                    user()->addFlash(sprintf(t('Password reset instructions have been sent to %s. Please check your email.'), $user->email), 'success');
                    Yii::app()->cache->delete("recover.attempt.{$_SERVER['REMOTE_ADDR']}");
                    $this->redirect(array('/user/login'));
                }
                Yii::app()->cache->set("recover.attempt.{$_SERVER['REMOTE_ADDR']}", ++$attempts);

            }
            // display the recover form
            $this->layout = 'narrow';
            $this->render('recover', array(
                'user' => $userRecover,
                'recaptcha' => ($attempts >= 3 && Setting::item('app', 'recaptcha')) ? true : false,
            ));
        }
    }

    /**
     * Logs out the current user and redirect to homepage.
     */
    public function actionLogout()
    {
        user()->logout();
        $this->redirect(Yii::app()->homeUrl);

    }

    /**
     * Lists all users.
     */
    public function actionIndex()
    {
        $user = new User('search');
        $this->render('index', array(
            'user' => $user,
        ));
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionLog($id)
    {
        $user = $this->loadModel($id);
        $this->render('log', array(
            'user' => $user,
        ));
    }

    /**
     * Displays a particular user.
     * @param integer $id the ID of the user to be displayed
     */
    public function actionView($id)
    {
        $user = $this->loadModel($id);

        // save in the menu
        DynamicMenu::add(array(
            'label' => 'user-' . $user->id,
            'url' => array('user/view', 'id' => $user->id),
        ));

        // check for deleted user
        if ($user->deleted) {
            user()->addFlash('THIS USER IS DELETED', 'error');
        }

        // update last_viewed
        //$user->last_viewed = time();
        //$user->save();

        $this->render('view', array(
            'user' => $user,
        ));
    }

    /**
     * Creates a new user.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {
        $user = new User('create');
        $this->performAjaxValidation($user, 'user-form');
        $rolePreselect = false;

        if (isset($_POST['User'])) {
            $error = false;
            $user->attributes = $_POST['User'];
            if (isset($_POST['User']['Role'])) {
                $rolePreselect = $_POST['User']['Role'];
            }

            // validate the data
            if (!$error) {
                if (!$user->validate()) {
                    $error = true;
                }
                if (!isset($_POST['User']['Role'])) {
                    $user->addError('role_id', t('You must select at least one role.'));
                    $error = true;
                }
            }

            // save the user
            if (!$error) {
                $roleRelation = $this->getAuthorizedRoleRelations($user);
                if (empty($roleRelation)) {
                    $staffRole = false;
                }
                else {
                    $staffRole = in_array(2, $roleRelation);
                }
                $user->setRelationRecords('role', $roleRelation);
                if (empty($roleRelation)) {
                    $staffRole = false;
                }
                else {
                    $staffRole = in_array(2, $roleRelation);
                }
                if (isset($_POST['User']['Team']) && $staffRole) {
                    $user->setRelationRecords('team', $_POST['User']['Team']);
                }
                else {
                    $user->setRelationRecords('team', array());
                }
                if (!$user->save()) {
                    $error = true;
                }
            }

            // redirect
            if (!$error) {
                user()->addFlash(t('User has been created'), 'success');
                $this->redirect(ReturnUrl::getUrl($user->getUrl()));
            }
            else {
                user()->addFlash(t('User could not be created'), 'warning');
            }
        }
        else {
            if (isset($_GET['User'])) {
                $user->attributes = $_GET['User'];
                if (isset($_GET['User']['Role'])) {
                    $rolePreselect = $_GET['User']['Role'];
                }
            }
        }

        $this->render('create', array(
            'user' => $user,
            'rolePreselect' => $rolePreselect,
        ));
    }

    /**
     * Updates a particular user.
     * @param integer $id the ID of the user to be updated
     */
    public function actionUpdate($id)
    {
        $user = $this->loadModel($id);
        $this->performAjaxValidation($user, 'user-form');
        if ($user->login_enabled == User::LOGIN_ENABLED_TEMP) {
            $this->flashAndRedirect(t('This is a system user and cannot be modified.'), 'warning');
        }

        if (isset($_POST['User'])) {
            $error = false;
            $user->attributes = $_POST['User'];

            // validate the role
            if (!$error) {
                if (!isset($_POST['User']['Role'])) {
                    $error = true;
                }
            }

            // save the user
            if (!$error) {
                $roleRelation = $this->getAuthorizedRoleRelations($user);
                if (empty($roleRelation)) {
                    $staffRole = false;
                }
                else {
                    $staffRole = in_array(2, $roleRelation);
                }
                $user->setRelationRecords('role', $roleRelation);
                if (isset($_POST['User']['Team']) && $staffRole) {
                    $user->setRelationRecords('team', $_POST['User']['Team']);
                }
                else {
                    $user->setRelationRecords('team', array());
                }
                if (!$user->save()) {
                    $error = true;
                }
            }

            // redirect
            if (!$error) {
                user()->addFlash(t('User has been updated'), 'success');
                $this->redirect(ReturnUrl::getUrl($user->getUrl()));
            }
            else {
                user()->addFlash(t('User could not be updated'), 'warning');
            }
        }
        else {
            //set defaults
            $user->password = null;
        }

        $this->render('update', array(
            'user' => $user,
        ));
    }

    /**
     * Deletes a particular model.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id = null)
    {
        $ids = sfGrid($id);
        foreach ($ids as $id) {
            $user = User::model()->findByPk($id);
            user()->addFlash(sprintf('User user-%s has been deleted', $id), 'success');
            $user->delete();
        }
        if (!isset($_GET['ajax']))
            $this->redirect(ReturnUrl::getUrl());
    }

    /**
     * Undeletes a particular model.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionUndelete($id = null)
    {
        $ids = sfGrid($id);
        foreach ($ids as $id) {
            $user = User::model()->findByPk($id);
            user()->addFlash(sprintf('User user-%s has been undeleted', $id), 'success');
            $user->undelete();
        }
        if (!isset($_GET['ajax']))
            $this->redirect(ReturnUrl::getUrl());
    }

    /**
     * Looks up the sales rep for autocomplete
     * @param null $role_id
     */
    public function actionAutoCompleteLookup($role_id = null)
    {
        //if(Yii::app()->request->isAjaxRequest && isset($_GET['q']))
        //{
        //$role_id = 3;  //3 is sales rep
        $name = $_GET['q'];
        $limit = min($_GET['limit'], 50);

        $criteria = new CDbCriteria;
        $criteria->condition = "((t.first_name LIKE :sterm) OR (t.last_name LIKE :sterm) OR (CONCAT(t.first_name,' ',t.last_name) LIKE :sterm))";
        $criteria->params = array();
        $criteria->params[':sterm'] = '%' . $name . '%';

        if ($role_id) {
            $criteria->join = 'LEFT JOIN user_to_role u2r ON u2r.user_id=t.id AND u2r.role_id=:role_id';
            $criteria->condition .= ' AND u2r.role_id=:role_id';
            $criteria->params[':role_id'] = $role_id;
        }

        $criteria->limit = $limit;
        $users = User::model()->findAll($criteria);

        $returnVal = '';
        foreach ($users as $user) {
            $returnVal .= $user->getName() . '|' . $user->getAttribute('id') . "\n";
        }
        echo $returnVal;
        //}
    }

    /**
     * @param $id
     */
    public function actionChangeUser($id)
    {
        $proxyUser = $this->loadModel($id, 'User');
        $originalUser = User::model()->findByPk(user()->id);

        $identity = new UserIdentity($proxyUser->email, null);
        if ($identity->authenticateChangeUser()) {
            user()->login($identity, $duration = 0);
        }

        Log::model()->add($originalUser->getLoginName() . " logged in as " . $proxyUser->getLoginName(), array(
            'model' => 'User',
            'model_id' => $originalUser->id,
        ));

        $this->redirect(Yii::app()->homeUrl);
    }


    /**
     * @return string
     */
    private function getAuthorizedUpdateAction()
    {
        if (user()->checkAccess('admin')) {
            $staffUpdate = 'update';
        }
        else {
            $staffUpdate = 'updateDummy';
            if ($this->action->id == 'update') {
                $id = sf('id', 'User');
                if ($id) {
                    $user = self::loadModel($id);
                    $roleAllowedByStaff = true;
                    foreach ($user->role as $key => $_role) {
                        $existingRoles[$_role->id] = $_role->id;
                        if (in_array($_role->id, $user->staffAllowedRoles)) {
                            //staff allowed to edit this role
                        }
                        else {
                            $roleAllowedByStaff = false;
                        }
                    }
                    if ($roleAllowedByStaff) {
                        $staffUpdate = 'update';
                    }
                }
            }
        }
        return $staffUpdate;
    }

    /**
     * @param $user
     * @return array
     */
    private function getAuthorizedRoleRelations($user)
    {
        $existingRoles = array();
        foreach ($user->role as $key => $_role) {
            $existingRoles[$_role->id] = $_role->id;
        }
        if (isset($_POST['User']['Role'])) {
            $roleRelation = $_POST['User']['Role'];
            if (user()->checkAccess('admin')) {
                //do nothing
            }
            else { //staff user
                foreach ($roleRelation as $key => $value) {
                    if (in_array($value, $user->staffAllowedRoles)) {
                    }
                    else {
                        if (isset($existingRoles[$key])) {
                            //allow existing roles
                        }
                        else {
                            unset ($roleRelation[$key]);
                        }
                    }
                }
            }
        }
        else {
            $roleRelation = array();
        }
        return $roleRelation;
    }

}
