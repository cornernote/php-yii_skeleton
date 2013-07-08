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
        Log::model()->add('sendRecoverPasswordEmail', $relation);
    }

    /**
     * @param $locksmith Locksmith
     */
    public function sendLocksmithWelcomeEmail($locksmith)
    {
        // setup email variables
        $to = array($locksmith->email => $locksmith->name);
        $modelsForParams = array('locksmith' => $locksmith);
        $relation = array('model' => 'LocksmithWelcomeEmail', 'model_id' => $locksmith->id);
        $viewParams = array();

        // spool the email
        $this->spool($to, 'locksmith.welcome', $viewParams, $modelsForParams, $relation);

        // tell someone about it
        Log::model()->add('sendLocksmithWelcomeEmail', $relation);
    }

    /**
     * @param $customer Customer
     */
    public function sendCustomerWelcomeEmail($customer)
    {
        // setup email variables
        $to = array($customer->email => $customer->name);
        $modelsForParams = array('customer' => $customer);
        $relation = array('model' => 'CustomerWelcomeEmail', 'model_id' => $customer->id);
        $viewParams = array();

        // get recovery temp login link
        $token = Token::model()->add('+7days', 1, $relation);
        $viewParams['url'] = url(array('/account/passwordReset', 'id' => $customer->id, 'token' => $token));

        // spool the email
        $this->spool($to, 'customer.welcome', $viewParams, $modelsForParams, $relation);

        // tell someone about it
        Log::model()->add('sendCustomerWelcomeEmail', $relation);
    }

    /**
     * @param $keyHolder KeyHolder
     */
    public function sendKeyHolderWelcomeEmail($keyHolder)
    {
        // setup email variables
        $to = array($keyHolder->email => $keyHolder->name);
        $modelsForParams = array('keyHolder' => $keyHolder);
        $relation = array('model' => 'KeyHolderWelcomeEmail', 'model_id' => $keyHolder->id);
        $viewParams = array();

        // get recovery temp login link
        $token = Token::model()->add('+7days', 1, $relation);
        $viewParams['url'] = url(array('/account/passwordReset', 'id' => $keyHolder->id, 'token' => $token));

        // spool the email
        $this->spool($to, 'keyHolder.welcome', $viewParams, $modelsForParams, $relation);

        // tell someone about it
        Log::model()->add('sendKeyHolderWelcomeEmail', $relation);
    }

    /**
     * @param $order Order
     */
    public function sendOrderCreateEmail($order)
    {
        $this->sendOrderCreateCustomerEmail($order);
        $this->sendOrderCreateLocksmithEmail($order);
    }

    /**
     * @param $order Order
     */
    public function sendOrderCreateCustomerEmail($order)
    {
        // setup email variables
        $to = array($order->customer->email => $order->customer->name);
        $modelsForParams = array('order' => $order, 'customer' => $order->customer, 'locksmith' => $order->locksmith);
        $relation = array('model' => 'OrderCreateCustomerEmail', 'model_id' => $order->id);
        $viewParams = array();

        // spool the email
        $this->spool($to, 'order.create.customer', $viewParams, $modelsForParams, $relation);

        // tell someone about it
        Log::model()->add('sendOrderCreateCustomerEmail', $relation);
    }

    /**
     * @param $order Order
     */
    public function sendOrderCreateLocksmithEmail($order)
    {
        // setup email variables
        $to = array($order->locksmith->email => $order->locksmith->name);
        $modelsForParams = array('order' => $order, 'customer' => $order->customer, 'locksmith' => $order->locksmith);
        $relation = array('model' => 'OrderCreateLocksmithEmail', 'model_id' => $order->id);
        $viewParams = array();

        // spool the email
        $this->spool($to, 'order.create.locksmith', $viewParams, $modelsForParams, $relation);

        // tell someone about it
        Log::model()->add('sendOrderCreateLocksmithEmail', $relation);
    }

    /**
     * @param $order Order
     */
    public function sendOrderUpdateEmail($order)
    {
        $this->sendOrderUpdateCustomerEmail($order);
        $this->sendOrderUpdateLocksmithEmail($order);
    }

    /**
     * @param $order Order
     */
    public function sendOrderUpdateCustomerEmail($order)
    {
        // setup email variables
        $to = array($order->customer->email => $order->customer->name);
        $modelsForParams = array('order' => $order, 'customer' => $order->customer, 'locksmith' => $order->locksmith);
        $relation = array('model' => 'OrderUpdateCustomerEmail', 'model_id' => $order->id);
        $viewParams = array();

        // spool the email
        $this->spool($to, 'order.update.customer', $viewParams, $modelsForParams, $relation);

        // tell someone about it
        Log::model()->add('sendOrderUpdateCustomerEmail', $relation);
    }

    /**
     * @param $order Order
     */
    public function sendOrderUpdateLocksmithEmail($order)
    {
        // setup email variables
        $to = array($order->locksmith->email => $order->locksmith->name);
        $modelsForParams = array('order' => $order, 'customer' => $order->customer, 'locksmith' => $order->locksmith);
        $relation = array('model' => 'OrderUpdateLocksmithEmail', 'model_id' => $order->id);
        $viewParams = array();

        // spool the email
        $this->spool($to, 'order.update.locksmith', $viewParams, $modelsForParams, $relation);

        // tell someone about it
        Log::model()->add('sendOrderUpdateLocksmithEmail', $relation);
    }

    /**
     * @param $count int
     */
    public function sendError($count)
    {
        $relation = array('model' => 'Error', 'foreign_key' => 0);

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