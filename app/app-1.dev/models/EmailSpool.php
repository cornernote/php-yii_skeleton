<?php

/**
 * --- BEGIN GenerateProperties ---
 *
 * This is the model class for table 'email_spool'
 *
 * @method EmailSpool with() with()
 * @method EmailSpool find() find($condition, array $params = array())
 * @method EmailSpool[] findAll() findAll($condition = '', array $params = array())
 * @method EmailSpool findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method EmailSpool[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method EmailSpool findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method EmailSpool[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method EmailSpool findBySql() findBySql($sql, array $params = array())
 * @method EmailSpool[] findAllBySql() findAllBySql($sql, array $params = array())
 *
 * Methods from behavior SoftDeleteBehavior
 * @method undelete() undelete()
 * @method deleteds() deleteds()
 * @method notdeleteds() notdeleteds()
 *
 * Properties from relation
 * @property Attachment[] $attachment
 *
 * Properties from table fields
 * @property integer $id
 * @property string $status
 * @property string $model
 * @property integer $model_id
 * @property string $to_email
 * @property string $to_name
 * @property string $from_email
 * @property string $from_name
 * @property string $message_subject
 * @property string $message_html
 * @property string $message_text
 * @property datetime $sent
 * @property datetime $created
 * @property datetime $deleted
 *
 * --- END GenerateProperties ---
 */
class EmailSpool extends CActiveRecord
{

    /**
     * @var array
     */
    public $attachments = array();

    /**
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return EmailSpool the static model class
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
        return 'email_spool';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => t('ID'),
            'status' => t('Status'),
            'model' => t('Model'),
            'model_id' => t('Model ID'),
            'to_email' => t('To Email'),
            'to_name' => t('To Name'),
            'from_email' => t('From Email'),
            'from_name' => t('From Name'),
            'message_subject' => t('Message Subject'),
            'message_html' => t('Message Html'),
            'message_text' => t('Message Text'),
            'sent' => t('Sent'),
            'created' => t('Created'),
            'deleted' => t('Deleted'),
        );
    }

    /**
     * @return array containing model behaviours
     */
    public function behaviors()
    {
        return array(
            'AuditBehavior' => 'behaviors.AuditBehavior',
            'SoftDeleteBehavior' => 'behaviors.SoftDeleteBehavior',
            'TimestampBehavior' => 'behaviors.TimestampBehavior',
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return array(
            'attachment' => array(
                self::HAS_MANY,
                'Attachment',
                'model_id',
                'condition' => 'attachment.model=:model AND deleted IS NULL',
                'params' => array(':model' => 'EmailSpool'),
                'order' => 'weight',
            ),
        );
    }

    /**
     * @param array $rules
     * @return array validation rules for model attributes.
     */
    public function rules($rules = array())
    {
        $rules = array();

        // search
        if ($this->scenario == 'search') {
            $rules[] = array('id, status, message_subject, to_name, to_email, from_name, from_email, model, model_id', 'safe', 'on' => 'search');
        }

        return $rules;
    }

    /**
     * @param null $options
     * @return string
     */
    public function getUrl($options = null)
    {
        $params = !empty($options['params']) ? $options['params'] : array();
        return CMap::mergeArray($params, array(
            '/emailSpool/view',
            'id' => $this->id,
        ));
    }

    /**
     * @param array $parts
     * @param array $urlOptions
     * @return string
     */
    public function getLink($parts = array(), $urlOptions = array())
    {
        return l($this->id, $this->getUrl($urlOptions));
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
        $criteria->compare('t.status', $this->status);
        $criteria->compare('t.model', $this->model, true);
        $criteria->compare('t.model_id', $this->model_id, true);
        $criteria->compare('t.to_email', $this->to_email, true);
        $criteria->compare('t.to_name', $this->to_name, true);
        $criteria->compare('t.from_email', $this->from_email);
        $criteria->compare('t.from_name', $this->from_name);
        $criteria->compare('t.message_subject', $this->message_subject);

        $criteria->addCondition('t.deleted IS ' . ($this->deleted == 'deleted' ? 'NOT NULL' : 'NULL'));

        return new ActiveDataProvider(get_class($this), CMap::mergeArray(array(
            'criteria' => $criteria,
        ), $options));
    }

    /**
     * Find pending emails and attempt to deliver them
     */
    public static function spool()
    {
        // find all the spooled emails
        $spools = EmailSpool::model()->findAll(array(
            'condition' => 't.status=:status',
            'params' => array(':status' => 'pending'),
            'order' => 't.id ASC',
            'limit' => '10',
        ));
        foreach ($spools as $spool) {

            // update status to emailing
            $spool->status = 'processing';
            $spool->save();

            // build the message
            $SM = app()->swiftMailer;
            $message = $SM->newMessage($spool->message_subject);
            $message->setFrom($spool->from_name ? array($spool->from_email => $spool->from_name) : array($spool->from_email));
            $message->setTo($spool->to_name ? array($spool->to_email => $spool->to_name) : array($spool->to_email));
            $message->setBody($spool->message_text);
            $message->addPart($spool->message_html, 'text/html');
            if (!empty($spool->attachments)) {
                foreach ($spool->attachment as $attachment) {
                    $message->attach(Swift_Attachment::fromPath($attachment->getAttachmentFile()));
                }
            }

            // send the email and update status
            $Transport = $SM->mailTransport();
            $Mailer = $SM->mailer($Transport);
            if ($Mailer->send($message)) {
                $spool->status = 'emailed';
                $spool->sent = date('Y-m-d H:i:s');
            }
            else {
                $spool->status = 'error';
            }
            $spool->save();

        }
    }

}