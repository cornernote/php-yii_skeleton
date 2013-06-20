<?php

class Log extends ActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return \CActiveRecord|\Log the static model class
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
        return 'log';
    }

    /**
     * Add a row to the log table
     * @param $message
     * @param array $options
     * @return bool|\Log
     */
    public function add($message, $options = array())
    {
        $log = new Log;
        $log->ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : null;
        $log->message = $message;
        $log->user_id = user()->id;
        $log->created = date('Y-m-d H:i:s');
        if (isset($options['details'])) {
            $log->details = serialize($options['details']);
        }
        if (isset($options['model'])) {
            $log->model = $options['model'];
            if (isset($options['model_id'])) {
                $log->model_id = $options['model_id'];
            }
        }
        if ($log->save()) {
            return $log;
        }
        else {
            return false;
        }

    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            // search fields
            array('id, ip, type, created, status, details', 'safe', 'on' => 'search'),
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
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => t('ID'),
            'ip' => t('IP'),
            'type' => t('Type'),
            'created' => t('Created'),
            'status' => t('Status'),
            'details' => t('Details'),
        );
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
        $criteria->compare('t.model_id', $this->model_id);
        $criteria->compare('t.ip', $this->ip, true);
        $criteria->compare('t.created', $this->created);
        $criteria->compare('t.details', $this->details);
        if ($this->messageSearch == 'start') {
            $criteria->addSearchCondition('t.message', $this->message . '%', $escape = false);
        }
        elseif ($this->messageSearch == 'exact') {
            $criteria->compare('t.message', $this->message);
        }

        return new ActiveDataProvider(get_class($this), CMap::mergeArray(array(
            'criteria' => $criteria,
        ), $options));
    }
}