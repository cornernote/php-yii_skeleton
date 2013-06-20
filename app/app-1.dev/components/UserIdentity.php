<?php
/**
 *
 */
class UserIdentity extends CUserIdentity
{
    /**
     * @var
     */
    private $_id;

    /**
     * Authentication for Standard Login
     *
     * @return bool
     */
    public function authenticate()
    {
        $username = strtolower($this->username);
        $user = User::model()->find('(LOWER(username)=? OR LOWER(email)=?) AND status=1 AND deleted IS NULL', array(
            $username,
            $username,
        ));
        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
            if (!$user->validatePassword($this->password))
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            else {
                $this->_id = $user->id;
                $this->username = $user->username ? $user->username : $user->email;
                $this->errorCode = self::ERROR_NONE;
            }
        }

        Log::model()->add('authenticate', array(
            'model' => 'UserIdentity',
            'model_id' => $this->errorCode,
            'details' => array(
                'username' => $username,
                'errorCode' => $this->errorCode,
            ),
        ));

        // returns true if no error, false if error
        return $this->errorCode == self::ERROR_NONE;
    }

    /**
     * Authentication for API Login
     *
     * @return bool
     */
    public function authenticateApi()
    {
        $username = strtolower($this->username);
        $user = User::model()->find('(LOWER(username)=? OR LOWER(email)=?) AND api_status=1 AND deleted IS NULL', array(
            $username,
            $username,
        ));
        if ($user === null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
            if (!$user->validatePassword($this->password, $user->api_key))
                $this->errorCode = self::ERROR_PASSWORD_INVALID;
            else {
                user()->setState('UserIdentity.api', true);
                $this->_id = $user->id;
                $this->username = $user->username ? $user->username : $user->email;
                $this->errorCode = self::ERROR_NONE;
            }
        }

        Log::model()->add('authenticateApi', array(
            'model' => 'UserIdentity',
            'model_id' => $this->errorCode,
            'details' => array(
                'username' => $username,
                'errorCode' => $this->errorCode,
            ),
        ));

        // returns true if no error, false if error
        return $this->errorCode == self::ERROR_NONE;
    }

    /**
     * Authentication for Change User
     *
     * @return bool
     */
    public function authenticateChangeUser()
    {
        $username = strtolower($this->username);
        $user = User::model()->find('(LOWER(username)=? OR LOWER(email)=?) AND status=1 AND deleted IS NULL', array(
            $username,
            $username,
        ));
        if ($user === null) {
            return $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
        if ($this->password != user()->user->password)
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {
            $this->_id = $user->id;
            $this->username = $user->username ? $user->username : $user->email;
            $this->errorCode = self::ERROR_NONE;
        }
        Log::model()->add('authenticateChangeUser', array(
            'model' => 'UserIdentity',
            'model_id' => $this->errorCode,
            'details' => array(
                'username' => $username,
                'errorCode' => $this->errorCode,
            ),
        ));
        // returns true if no error, false if error
        return $this->errorCode == self::ERROR_NONE;
    }

    /**
     * Authentication for Secure Link
     *
     * @return bool
     */
    public function authenticateSecureLink()
    {
        $username = strtolower($this->username);
        $user = User::model()->find('(LOWER(username)=? OR LOWER(email)=?) AND status=1 AND deleted IS NULL', array(
            $username,
            $username,
        ));
        if ($user === null) {
            return $this->errorCode = self::ERROR_USERNAME_INVALID;
        }
        if ($this->password != $user->password)
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else {
            $this->_id = $user->id;
            $this->username = $user->username ? $user->username : $user->email;
            $this->errorCode = self::ERROR_NONE;
        }

        Log::model()->add('authenticateSecureLink', array(
            'model' => 'UserIdentity',
            'model_id' => $this->errorCode,
            'details' => array(
                'username' => $username,
                'errorCode' => $this->errorCode,
            ),
        ));
        // returns true if no error, false if error
        return $this->errorCode == self::ERROR_NONE;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->_id;
    }

}
