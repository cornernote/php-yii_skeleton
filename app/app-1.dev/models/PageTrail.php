<?php

class PageTrail extends ActiveRecord
{
    /**
     * @var
     */
    public $model;

    /**
     * @var
     */
    public $model_id;

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return \CActiveRecord|\PageTrail the static model class
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
        return 'page_trail';
    }

    /**
     * @return array|mixed|null validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('id, user_id, link, created, app_version, yii_version, audit_trail_count, total_time, memory_usage, memory_peak, model, model_id', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'user' => array(
                self::BELONGS_TO,
                'User',
                'user_id',
            ),
            'auditTrail' => array(
                self::HAS_MANY,
                'AuditTrail',
                'page_trail_id',
            ),
            'auditTrailCount' => array(
                self::STAT,
                'AuditTrail',
                'page_trail_id',
            ),
        );
    }

    /**
     * @param array $extraArgs
     * @return string url to view the model
     */
    public function getUrl($extraArgs = array())
    {
        return url('/pageTrail/view', array_merge(array(
            'id' => $this->id,
        ), (array)$extraArgs));
    }

    /**
     * @return string
     */
    public function getLink()
    {
        return l('pageTrail-' . $this->id, $this->getUrl());
    }

    /**
     * @return string
     */
    public function getLinkString()
    {
        $link = $this->link;
        $path = Yii::app()->getRequest()->getHostInfo() . bu();
        if (strpos($link, $path) === 0) {
            $link = substr($link, strlen($path));
        }
        if (strlen($link) < 64)
            return $link;
        return substr($link, 0, 64) . '...';
    }

    /**
     * @static
     * @param $linkGiven
     * @return string
     */
    static function reverseLinkString($linkGiven)
    {
        if (strpos($linkGiven, '/') === 0) {
            $path = Yii::app()->getRequest()->getHostInfo() . bu();
            $result = $path . $linkGiven;
            return $result;
        }
        else {
            return $linkGiven;
        }
    }

    /**
     *
     */
    public function recordPageTrail()
    {
        //saving is done in updatePageTrail() pageTrail->save() function kind of thing
        //$this->created_gmtime = gmdate('Y-m-d h:i:s');
        $this->created = date('Y-m-d H:i:s');
        //$this->user_id = user()->id;
        $this->session = $this->getShrinkedSession();
        $this->files = $_FILES;
        $this->link = Yii::app()->getRequest()->getHostInfo() . Yii::app()->getRequest()->getUrl();
        if (isset($_SERVER['REMOTE_ADDR'])) {
            $this->ip = $_SERVER['REMOTE_ADDR'];
        }
        if (isset($_SERVER['HTTP_REFERER'])) {
            $this->referrer = $_SERVER['HTTP_REFERER'];
        }
        $this->app_version = Setting::item('core', 'app_version');
        $this->yii_version = Setting::item('core', 'yii_version');
        $this->start_time = $GLOBALS['start'];

        $this->removePasswords();
        //serialize , compress and base64encode
        $this->pack('post');
        $this->pack('get');
        $this->pack('files');
        $this->pack('cookie');
        $this->pack('server');
        $this->pack('session');
        $this->save();
        static_id('page_trail_id', $this->id); // used for AuditTrail tracking

        // update the user id after the first save
        $this->user_id = user()->id;
        $this->save();
    }

    /**
     *
     */
    function removePasswords()
    {
        $post = $_POST;
        $get = $_GET;
        $server = $_SERVER;
        $passwordRemovedFromGet = self::removedValuesWithPasswordKeys($get);
        $passwordRemovedFromPost = self::removedValuesWithPasswordKeys($post);
        self::removedValuesWithPasswordKeys($server);
        $this->get = $get;
        $this->post = $post;
        if (!$passwordRemovedFromGet && !$passwordRemovedFromPost) {
            $this->server = $server;
        }
        if ($passwordRemovedFromGet) {
            $this->link = '';
        }
        $this->cookie = $_COOKIE;
    }

    /**
     * @param $attribute
     */
    function pack($attribute)
    {
        $value = $this->$attribute;
        //already packed
        @$alreadyDecoded = is_array(unserialize(gzuncompress(base64_decode($value))));
        if ($alreadyDecoded) {
            echo "<br/> already decoded  <br/>\r\n";
            return;
        }
        $value = serialize($value);
        $value = gzcompress($value);
        $value = base64_encode($value);
        $this->$attribute = $value;
    }

    /**
     * @param $attribute
     * @return mixed
     */
    function unpack($attribute)
    {
        @$value = unserialize($this->$attribute);
        if ($value !== false) {
            $this->$attribute = $value;
            return;
        }
        $value = base64_decode($this->$attribute);
        if (!$value) {
            return;
        }

        @$value = gzuncompress($value);
        if ($value === false) {
            $this->$attribute = "could not uncompress [" . var_dump($value) . "]";
            return;
        }
        @$value = unserialize($value);
        if ($value === false) {
            $this->$attribute = "could not unserialize [" . var_dump($value) . "]";
        }
        $this->$attribute = $value;

    }

    /**
     *
     */
    protected function afterFind()
    {
        parent::afterFind();
        $this->unpack('post');
        $this->unpack('get');
        $this->unpack('files');
        $this->unpack('cookie');
        $this->unpack('server');
        $this->unpack('session');
    }

    /**
     * @static
     * @param $array
     * @return bool
     */
    static function removedValuesWithPasswordKeys(&$array)
    {
        if (!$array) {
            return false;
        }
        $removed = false;
        foreach ($array as $key => $value) {
            if (stripos($key, 'password') !== false) {
                $array[$key] = 'Possible password removed';
                $removed = true;
            }
            elseif (stripos($key, 'PHP_AUTH_PW') !== false) {
                $array[$key] = 'Possible password removed';
                $removed = true;
            }
            else {
                if (is_array($value)) {
                    $removedChild = self::removedValuesWithPasswordKeys($value);
                    if ($removedChild) {
                        $array[$key] = $value;
                        $removed = true;
                    }
                }
            }
        }
        return $removed;
    }

    /**
     *
     */
    protected function updatePageTrail()
    {
        $headers = headers_list();
        foreach ($headers as $header) {
            if (strpos(strtolower($header), 'location:') === 0) {
                $this->redirect = trim(substr($header, 9));
            }
        }
        $this->memory_usage = memory_get_usage();
        $this->memory_peak = memory_get_peak_usage();
        $this->end_time = microtime(true);
        $this->total_time = $this->end_time - $this->start_time;
        $this->audit_trail_count = $this->auditTrailCount;
        $this->save();
    }

    /**
     * @return mixed
     */
    public function getShrinkedSession()
    {
        $serialized = '';
        if (isset($_SESSION)) {
            $serialized = serialize($_SESSION);
        }
        if (strlen($serialized) > 64000) {
            $sessionCopy = $_SESSION;
            $ignoredKeys = array();
            foreach ($_SESSION as $key => $value) {
                $size = strlen(serialize($value));
                if ($size > 1000) {
                    unset($sessionCopy[$key]);
                    $ignoredKeys[$key] = $key;
                }
            }
            $sessionCopy['z_ignored_keys_in_page_trail'] = $ignoredKeys;
            $serialized = serialize($sessionCopy);
        }
        return unserialize($serialized);
    }


    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @param array $options
     * @return ActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($options = array())
    {
        $criteria = new CDbCriteria;
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.user_id', $this->user_id);

        //$fullLink = self::reverseLinkString($this->link);
        $criteria->compare('t.link', $this->link, true);
        $criteria->compare('t.created', $this->created);
        $criteria->compare('t.app_version', $this->app_version);
        $criteria->compare('t.yii_version', $this->yii_version);
        $criteria->compare('t.audit_trail_count', $this->audit_trail_count);
        $criteria->compare('t.total_time', $this->total_time);
        $criteria->compare('t.memory_usage', $this->memory_usage);
        $criteria->compare('t.memory_peak', $this->memory_peak);
        $criteria->mergeWith($this->getDbCriteria());

        if ($this->model) {
            $criteria->distinct = true;
            $criteria->compare('t.audit_trail_count', '>0');
            //$criteria->group = 't.id';
            $criteria->join .= ' INNER JOIN audit_trail ON audit_trail.page_trail_id=t.id ';
            $criteria->compare('audit_trail.model', $this->model);
            if ($this->model_id) {
                $criteria->compare('audit_trail.model_id', $this->model_id);
            }
        }

        return new ActiveDataProvider(get_class($this), CMap::mergeArray(array(
            'criteria' => $criteria,
        ), $options));
    }

    /**
     * @return string
     */
    function showYiiVersion()
    {
        $startPos = strpos($this->yii_version, 'yii-');
        $endPos = strpos($this->yii_version, '.r', $startPos);
        $len = $endPos - $startPos;
        $shortVersion = substr($this->yii_version, $startPos, $len);
        return substr($shortVersion, 4);
        //$icon = l(i(au() . '/icons/comments.png'), 'javascript:void();', array('title' => $this->yii_version));
        //return $icon . '&nbsp;' . $shortVersion;
    }


    /**
     * @return bool|string
     */
    function showAppVersion()
    {
        return $this->app_version;
        //return l(i(au() . '/icons/comments.png'), 'javascript:void();', array('title' => $this->app_version));
    }

}