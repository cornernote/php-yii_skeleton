<?php
/**
 * EMailManager
 */
class EMailManager extends CApplicationComponent
{

    /**
     * @var string
     */
    public $layout = 'layout.default';

    /**
     * @param $user User
     */
    public function sendRecoverPasswordEmail($user)
    {
        // setup email variables
        $to = array($user->email => $user->name);
        $modelsForParams = array('user' => $user);
        $relation = array('model' => 'RecoverPasswordEmail', 'model_id' => $user->id);
        $viewParams = array();

        // get recovery temp login link
        $token = Token::model()->add('+1day', 1, $relation);
        $viewParams['url'] = url(array('/account/passwordReset', 'id' => $user->id, 'token' => $token));

        // spool the email
        $this->spool($to, 'user.recover', $viewParams, $modelsForParams, $relation);

        // tell someone about it
        Log::model()->add('EMailManager::sendRecoverPasswordEmail', $relation);
    }

    /**
     * @param $user User
     */
    public function sendWelcomeEmail($user)
    {
        // setup email variables
        $to = array($user->email => $user->name);
        $modelsForParams = array('user' => $user);
        $relation = array('model' => 'WelcomeEmail', 'model_id' => $user->id);
        $viewParams = array();

        // spool the email
        $this->spool($to, 'user.welcome', $viewParams, $modelsForParams, $relation);

        // tell someone about it
        Log::model()->add('EMailManager::sendWelcomeEmail', $relation);
    }

    /**
     * @param $count int
     */
    public function sendError($count)
    {
        $relation = array('model' => 'Error', 'model_id' => 0);

        $url = 'http://' . param('domain') . url('/error/index');
        $messageString = t('errors have been archived') . ' ' . $url;

        $message = array(
            'heading' => null,
            'subject' => t('errors have been archived'),
            'text' => $messageString,
            'html' => format()->formatNtext($messageString),
        );

        // email the given user
        $tos = explode(',', Setting::item('core', 'error_email'));
        foreach ($tos as $to) {
            $to = trim($to);
            EmailSpool::model()->spool($to, $message, $relation);
        }
    }

    /**
     * Spool (save) an email
     * @param $to string|array
     * @param $template
     * @param array $viewParams
     * @param array $modelsForParams
     * @param array $relation
     * @throws CException
     * @return bool|integer
     */
    private function spool($to, $template, $viewParams = array(), $modelsForParams = array(), $relation = array())
    {
        // generate the message
        $message = $this->renderEmailTemplate($template, $viewParams, $modelsForParams);

        // format the to_name/to_email
        $to_email = $to_name = '';
        if (!is_array($to)) {
            $to = array($to => '');
        }
        foreach ($to as $to_email => $to_name)
            break;
        if (!$to_email) {
            $to_email = $to_name;
            $to_name = '';
        }

        // save the email
        $emailSpool = new EmailSpool;
        $emailSpool->status = 'pending';
        $emailSpool->from_email = Setting::item('app', 'email');
        $emailSpool->from_name = app()->name;
        $emailSpool->to_email = $to_email;
        $emailSpool->to_name = $to_name;
        $emailSpool->message_subject = $message['message_subject'];
        $emailSpool->message_text = $message['message_text'];
        $emailSpool->message_html = $message['message_html'];
        if (isset($relation['model'])) {
            $emailSpool->model = $relation['model'];
            if (isset($relation['model_id'])) {
                $emailSpool->model_id = $relation['model_id'];
            }
        }

        // set flash message
        $flash = true;
        if (Setting::item('core', 'debug_email'))
            $flash = true;
        elseif (!user()->checkAccess('admin'))
            $flash = false;
        elseif (isset($options['flash']))
            $flash = $options['flash'];
        if ($flash && isset(Yii::app()->controller)) {
            $debug = Yii::app()->controller->renderPartial('application.views.email._debug', compact('to', 'message', 'template'), true);
            user()->addFlash($debug, 'email');
        }

        // return the id
        if ($emailSpool->save()) {
            return $emailSpool->id;
        }
        throw new CException('could not save email spool because ' . $emailSpool->getErrorString());
    }


    /**
     * @param $template string
     * @param $viewParams array
     * @param array $modelsForParams
     * @throws CException
     * @return array
     */
    private function renderEmailTemplate($template, $viewParams = array(), $modelsForParams = array())
    {
        // load layout
        $emailLayout = EmailTemplate::model()->findByAttributes(array('name' => $this->layout));
        if (!$emailLayout)
            throw new CException('missing EmailTemplate - ' . $this->layout);

        // load template
        $emailTemplate = EmailTemplate::model()->findByAttributes(array('name' => $template));
        if (!$emailTemplate)
            throw new CException('missing EmailTemplate - ' . $template);

        // load params
        $params = CMap::mergeArray($this->getParams($modelsForParams), $viewParams);

        $mustache = new Mustache;
        $templates = array();
        $fields = array('message_subject', 'message_html', 'message_text');
        foreach ($fields as $field) {
            $params['contents'] = $mustache->render($emailTemplate->$field, $params);
            $templates[$field] = $mustache->render($emailLayout->$field, $params);
            unset($params['contents']);
        }

        return $templates;
    }

    /**
     * @param $modelsForParams
     * @return array|mixed
     */
    private function getParams($modelsForParams)
    {
        // app params
        $params = array();

        // links
        $params['app__domain'] = Setting::item('app', 'domain');
        $params['app__au'] = au();

        // company
        $params['app__name'] = app()->name;
        $params['app__phone'] = Setting::item('app', 'phone');
        $params['app__email'] = Setting::item('app', 'email');
        $params['app__website'] = Setting::item('app', 'website');

        // model params
        foreach ($modelsForParams as $model) {
            if (method_exists($model, 'getEmailTemplateParams')) {
                $params = CMap::mergeArray($params, $model->getEmailTemplateParams());
            }
        }
        return $params;
    }
}