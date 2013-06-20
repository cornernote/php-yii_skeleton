<?php

class User extends ActiveRecord
{

    /**
     * @var string used to confirm change of password
     */
    public $confirm_password;

    /**
     * @var integer search by role_id
     */
    public $role_id;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return \CActiveRecord|\User the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'user';
    }

    /**
     * @return array containing model behaviours
     */
    public function behaviors()
    {
        return array(
            'AuditBehavior' => 'AuditBehavior',
            'SaveRelationsBehavior' => 'SaveRelationsBehavior',
            'EavBehavior' => array(
                'class' => 'EavBehavior',
                'tableName' => 'user_eav',
            ),
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        $rules = array(
            // search fields
            array('id, username, email, deleted, role_id, team_id, company_name, login_enabled', 'safe', 'on' => 'search'),

            // email
            array('email', 'required', 'on' => 'create, update, account'),
            array('email', 'length', 'max' => 255),
            array('email', 'email'),
            array('email', 'unique', 'criteria' => array('condition' => 't.deleted IS NULL')),

            // username
            array('username', 'length', 'max' => 255),
            array('username', 'unique', 'criteria' => array('condition' => 'deleted IS NULL')),

            // status
            array('status', 'numerical', 'integerOnly' => true, 'on' => 'create, update'),

            // api_status
            array('api_status', 'numerical', 'integerOnly' => true, 'on' => 'create, update'),

            // first_name
            array('first_name', 'required', 'on' => 'create, update, account'),
            array('first_name', 'length', 'max' => 255),

            // last_name
            array('last_name', 'required', 'on' => 'create, update, account'),
            array('last_name', 'length', 'max' => 255),

            // phone
            array('phone', 'length', 'max' => 255),

            // fax
            array('fax', 'length', 'max' => 255),
        );

        if (user()->checkAccess('admin')) {
            // password
            $rules[] = array('password', 'length', 'max' => 64, 'min' => 5);

            // confirm_password
            $rules[] = array('confirm_password', 'length', 'max' => 64, 'min' => 5);
            $rules[] = array('confirm_password', 'compare', 'compareAttribute' => 'password');
        }

        return $rules;
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'role' => array(
                self::MANY_MANY,
                'Role',
                'user_to_role(user_id, role_id)',
            ),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => t('ID'),
            'email' => t('Email'),
            'password' => t('Password'),
            'confirm_password' => t('Confirm Password'),
            'first_name' => t('First Name'),
            'last_name' => t('Last Name'),
            'role_id' => t('Role'),
        );
    }

    /**
     * @param $plain
     * @param null $encrypted
     * @return boolean validates a password
     */
    public function validatePassword($plain, $encrypted = null)
    {
        $encrypted = $encrypted ? $encrypted : $this->password;
        if ($plain && $encrypted) {
            $stack = explode(':', $encrypted);
            if (sizeof($stack) != 2) {
                return false;
            }
            if (sha1($stack[1] . $plain) == $stack[0]) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param $plain
     * @return string creates a password hash
     */
    public function hashPassword($plain)
    {
        $password = '';
        for ($i = 0; $i < 10; $i++) {
            $password .= mt_rand();
        }
        $salt = substr(sha1($password), 0, 2);
        $password = sha1($salt . $plain) . ':' . $salt;
        return $password;
    }

    /**
     * Actions to be performed before the model is saved
     * @return bool
     */
    public function beforeSave()
    {
        if (!parent::beforeSave()) {
            return false;
        }

        // hash the password if it is being changed
        if ($this->password && $this->confirm_password) {
            $this->password = $this->hashPassword($this->password);
        }

        // generate an api key
        if ($this->api_enabled && (!isset($this->dbAttributes['api_enabled']) || !$this->dbAttributes['api_enabled'])) {
            $plainApiKey = $this->getApiKey();
            if ($plainApiKey) {
                $this->api_key = $this->hashPassword($plainApiKey);
            }
        }
        if (!$this->api_enabled) {
            $this->api_key = null;
        }

        // if they have no password
        if (!$this->password) {
            // new user - assign password so that forgot password links work
            if ($this->isNewRecord) {
                $this->password = $this->hashPassword(md5(uniqid('', true)));
            }
            // admin updating user - get existing password
            else {
                $this->password = $this->dbAttributes['password'];
            }
        }

        return true;
    }

    /**
     * @return string
     */
    public function getApiKey()
    {
        if (!$this->api_enabled) {
            return '';
        }
        $password = $this->password;
        if (!$password && isset($this->dbAttributes['password'])) {
            $password = $this->dbAttributes['password'];
        }
        if (!$password) {
            return '';
        }
        return md5($password . Setting::item('app', 'hashKey'));
    }

    /**
     * @return array url to view the model
     */
    public function getUrl()
    {
        return url('/user/view', array(
            'id' => $this->id,
        ));
    }

    /**
     * @return string full name of the user
     */
    public function getName()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * @return mixed
     */
    public function getLoginName()
    {
        return $this->username ? $this->username : $this->email;
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @param array $options
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($options = array())
    {
        $criteria = new CDbCriteria;
        $criteria->compare('t.id', $this->getSearchField('id', 'id'));
        $criteria->compare('t.email', $this->email, true);
        $criteria->compare('t.username', $this->username, true);
        $criteria->compare('t.first_name', $this->first_name, true);
        $criteria->compare('t.last_name', $this->last_name, true);
        $criteria->compare('t.phone', $this->phone, true);
        if ($this->status === 0 || $this->status === '0') {
            $criteria->addCondition('t.status=0');
        }
        else {
            $criteria->compare('t.status', $this->status);
        }
        $criteria->compare('t.created', $this->created);

        if ($this->role_id) {
            $criteria->compare('u2r.role_id', $this->role_id, true);
            $criteria->join .= ' LEFT JOIN user_to_role u2r ON u2r.user_id=t.id AND u2r.role_id=:role_id';
            $criteria->params[':role_id'] = $this->role_id;
        }

        if ($this->deleted == 'deleted') {
            $criteria->addCondition('t.deleted IS NOT NULL');
        }
        else {
            $criteria->addCondition('t.deleted IS NULL');
        }

        return new ActiveDataProvider(get_class($this), CMap::mergeArray(array(
            'criteria' => $criteria,
        ), $options));
    }

    /**
     * Gets a link to the user
     * @param string $format
     * @return string
     */
    public function getLink($format = 'default')
    {
        if (!is_array($format)) $format = array($format);
        $update = '';
        if (in_array('update', $format)) {
            $update = l(i(au() . '/icons/update.png'), array('/user/update', 'id' => $this->id, 'returnUrl' => ReturnUrl::getLinkValue(true)));
        }
        if (in_array('inline', $format)) {
            return l('user-' . $this->id . ' ' . h($this->getName()), $this->getUrl()) . ' ' . $update;
        }
        return l('user-' . $this->id, $this->getUrl()) . ' ' . $update;
    }

    /**
     * @return string
     */
    public function getStatusString()
    {
        if ($this->status) {
            return t('enabled');
        }
        return t('disabled');
    }

    /**
     * @return string
     */
    public function getApiStatusString()
    {
        if ($this->api_status) {
            return t('enabled');
        }
        return t('disabled');
    }

    /**
     * @param $viewParams array
     * @return array
     */
    public function getTemplateHash($viewParams)
    {
        $viewParams['user_id'] = $this->id;
        $viewParams['user_first_name'] = $this->first_name;
        $viewParams['user_last_name'] = $this->last_name;
        $viewParams['user_name'] = $this->getName();
        $viewParams['user_ip'] = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'No IP';
        $viewParams['user_recovery_url'] = $this->getRecoveryUrl();
        $viewParams['user_app_name'] = Yii::app()->name;
        $viewParams['user_welcome_url'] = 'http://' . param('host');
        $viewParams['user_login_user_name'] = $this->username ? $this->username : $this->email;

        return $viewParams;
    }

    /**
     * @return string
     */
    public function getRecoveryUrl()
    {
        // get recovery temp login link
        $ttl = time() + (24 * 60 * 60);
        $url = SecureLink::getUrl('/user/recover', array(
            'email' => $this->email,
            'hash' => $this->password,
        ), $ttl);
        return $url;
    }

}