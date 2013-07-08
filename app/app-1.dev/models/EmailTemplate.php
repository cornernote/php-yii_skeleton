<?php

/**
 * This is the model class for table 'email_template'
 *
 *
 * @method EmailTemplate with() with()
 * @method EmailTemplate find() find($condition, array $params = array())
 * @method EmailTemplate[] findAll() findAll($condition = '', array $params = array())
 * @method EmailTemplate findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method EmailTemplate[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method EmailTemplate findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method EmailTemplate[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method EmailTemplate findBySql() findBySql(string $sql, array $params = array())
 * @method EmailTemplate[] findAllBySql() findAllBySql(string $sql, array $params = array())
 *
 *
 *
 *
 * Properties from relation
 *
 *
 * Properties from table fields
 * @property integer $id
 * @property string $name
 * @property string $message_subject
 * @property string $message_text
 * @property string $message_html
 * @property string $description
 * @property datetime $created
 * @property datetime $deleted
 *
 */


class EmailTemplate extends ActiveRecord
{
    /**
     *
     * Returns the static model of the specified AR class.
     * @param string $className
     * @return EmailTemplate the static model class
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
        return 'email_template';
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => t('ID'),
            'name' => t('Name'),
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
            'AuditBehavior' => 'AuditBehavior',
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
            $rules[] = array('id, name', 'safe', 'on' => 'search');
        }

        // create/update
        if (in_array($this->scenario, array('create', 'update'))) {

            // name
            $rules[] = array('name', 'required');
            $rules[] = array('name', 'length', 'max' => 255);

            // subject
            $rules[] = array('message_subject', 'required');
            $rules[] = array('message_subject', 'length', 'max' => 255);

            // text
            $rules[] = array('message_text', 'required');
            //$rules[] = array('message_text', 'length', 'max' => 255);

            // html
            $rules[] = array('message_html', 'required');
            //$rules[] = array('message_html', 'length', 'max' => 255);
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
            '/emailTemplate/view',
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
        $link = l($this->name, $this->getUrl($urlOptions));
        if (in_array('update', $parts) && user()->checkAccess('admin,locksmith')) {
            $link .= ' ' . l('', array('/emailTemplate/update', 'id' => $this->id, 'returnUrl' => ReturnUrl::getLinkValue(true)), array('class' => 'icon-pencil icon-grey', 'data-toggle' => 'modal-remote'));
        }
        return $link;
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
        $criteria->compare('t.name', $this->name, true);

        $criteria->addCondition('t.deleted IS ' . ($this->deleted == 'deleted' ? 'NOT NULL' : 'NULL'));

        return new ActiveDataProvider(get_class($this), CMap::mergeArray(array(
            'criteria' => $criteria,
        ), $options));
    }


    /**
     * @param Controller $controller
     * @param int $id
     * @return array
     */
    public function getPreviewModel($controller = null, $id = 0)
    {

        $return = array(
            'subject' => 'sbj',
            'text' => 'txt',
            'html' => 'htm',
        );
        if ($this->model_name == 'EmailTemplate') {
            return $return;
        }

        $id = $this->getPreviewId($id);
        if ($id) {
            $templateFullName = $this->model_name . '.' . $this->name;
            foreach ($return as $type => $v) {
                $contents = EmailInfo::renderTemplate($templateFullName, $type, $id);
                if ($type == 'html') {
                    $contents = nl2br($contents);
                    InternalLog::log("contents are : $contents");
                }

                //if ($controller) {
                $params = self::getParams(false, false);
                $params['contents'] = $contents;
                $contents = MustacheCom::render('EmailTemplate.default_' . $v, 'text', $params);
                //$viewPath = Yii::getPathOfAlias("application.views.emails.layouts.default_{$v}") . '.php';
                //$contents = $controller->renderInternal($viewPath, array('contents' => $contents), true);

                //}
                $return[$type] = $contents;
            }


        }
        return $return;
    }

    /**
     * @return EmailInfo
     */
    public function getNext()
    {
        return Helper::nextId(EmailInfo::model()->findAll(), $this);
    }

    /**
     * @return EmailInfo
     */
    public function getPrevious()
    {
        return Helper::previousId(EmailInfo::model()->findAll(), $this);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getPreviewId($id)
    {
        if ($id) {
            return $id;
        }
        $conditions = array();
        if (strpos($this->text, 'supplier')) {
            $conditions[] = $this->model_name . '.supplier_id IS NOT NULL ';
        }
        if (strpos($this->text, 'customer_approved_notes')) {
            $conditions[] = $this->model_name . '.customer_approved_notes IS NOT NULL AND TRIM(customer_approved_notes)<>"" ';
        }
        if (strpos($this->text, 'supplier_approved_notes')) {
            $conditions[] = $this->model_name . '.supplier_approved_notes IS NOT NULL AND TRIM(supplier_approved_notes)<>"" ';
        }
        if (strpos($this->text, 'pickup')) {
            $conditions[] = $this->model_name . '.pickup_id IS NOT NULL';
        }
        if (strpos($this->text, 'user') && ($this->model_name == 'user')) {
            $conditions[] = $this->model_name . '.email NOT LIKE "%temp%"';
        }


        $fullCondition = implode(' AND ', $conditions);
        if (count($conditions) > 0) {
            $fullCondition = ' WHERE ' . $fullCondition;
        }

        $sql = 'select max(id) from ' . $this->model_name . $fullCondition;
        $id = app()->db->createCommand($sql)->queryScalar();
        while (!$id && $conditions) {
            array_pop($conditions);
            $fullCondition = implode(' AND ', $conditions);
            if (count($conditions) > 0) {
                $fullCondition = ' WHERE ' . $fullCondition;
            }
            $sql = 'select max(id) from ' . $this->model_name . $fullCondition;
            $id = app()->db->createCommand($sql)->queryScalar();
        }
        return $id;

    }


}