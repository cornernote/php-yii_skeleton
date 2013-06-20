<?php

/**
 * UserLogin is the data structure for keeping user login form data.
 * It is used by the 'login' action of 'UserController'.
 *
 * @package app.model
 * @author Brett O'Donnell <brett@mrphp.com.au>
 */
class UserLogin extends FormModel
{

    /**
     * @var
     */
    public $username;

    /**
     * @var
     */
    public $password;

    /**
     * @var
     */
    public $remember_me;

    /**
     * @var
     */
    public $recaptcha;

    /**
     * @var UserIdentity
     */
    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that email and password are required,
     * and password needs to be authenticated.
     * @return array
     */
    public function rules()
    {
        $rules = array(
            // username
            array('username', 'required'),

            // password
            array('password', 'required'),
            array('password', 'authenticate', 'skipOnError' => true),

            // remember_me
            array('remember_me', 'boolean'),
        );
        // recaptcha
        if (Setting::item('app', 'recaptcha')) {
            $rules[] = array('recaptcha', 'EReCaptchaValidator', 'privateKey' => Setting::item('app', 'recaptchaPrivate'), 'on' => 'recaptcha');
        }
        return $rules;
    }

    /**
     * Declares attribute labels.
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'username' => t('Username'),
            'password' => t('Password'),
            'remember_me' => t('Remember me next time'),
            'recaptcha' => t('Enter both words separated by a space'),
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     * @param $attribute
     * @param $params
     */
    public function authenticate($attribute, $params)
    {
        $this->_identity = new UserIdentity($this->username, $this->password);
        if (!$this->_identity->authenticate()) {
            $this->addError('password', 'Incorrect username or password.');
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login()
    {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->username, $this->password);
        }
        if ($this->_identity->authenticate()) {
            $duration = $this->remember_me ? 3600 * 24 * 30 : 0; // 30 days
            user()->login($this->_identity, $duration);
            return true;
        }
        return false;
    }

}
