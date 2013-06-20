<?php
/**
 * AuditBehavior
 *
 * @property ActiveRecord $owner
 *
 * @package app.model.behavior
 * @author Brett O'Donnell <brett@mrphp.com.au>
 */
class AuditBehavior extends CActiveRecordBehavior
{
    /**
     * @var array
     */
    private $_oldattributes = array();
    /**
     * @var array
     */
    public $ignoreFields = array(
        'create' => array('modified', 'modified_by', 'deleted', 'deleted_by'),
        'update' => array('created', 'created_by', 'modified', 'modified_by'),
    );
    /**
     * @var array
     */
    public $ignoreValuess = array('0', '0000-00-00', '0000-00-00 00:00:00');

    /**
     * @param $event
     */
    public function afterSave($event)
    {
        try {
            $userid = user()->id;
        } catch (Exception $e) { //If we have no user object, this must be a command line program
            $userid = 0;
        }

        $newattributes = $this->owner->getAttributes();
        $oldattributes = $this->getOldAttributes();
        $pageTrailId = static_id('page_trail_id');

        // update
        if (!$this->owner->isNewRecord) {
            // compare old and new
            foreach ($newattributes as $name => $new) {
                if (in_array($name, $this->ignoreFields['update'])) continue;

                if (!empty($oldattributes)) {
                    $old = $oldattributes[$name];
                } else {
                    $old = '';
                }
                if (in_array($old, $this->ignoreValuess)) $old = '';
                if (in_array($new, $this->ignoreValuess)) $new = '';

                $old = trim($old);
                $new = trim($new);

                // log any changes
                if ($new != $old) {
                    $log = new AuditTrail();
                    $log->old_value = $old;
                    $log->new_value = $new;
                    $log->action = 'CHANGE';
                    //$log->model = get_class($this->owner);
                    $log->model = get_class($this->owner->auditModel);
                    //$log->model_id = $this->owner->getPrimaryKey();
                    $log->model_id = $this->owner->auditModel->getPrimaryKey();
                    $log->field = $this->fieldPrefix() . $name;
                    $log->created = date('Y-m-d H:i:s');
                    $log->user_id = $userid;
                    $log->page_trail_id = $pageTrailId;
                    $log->save();
                }
            }
        }

        // new record was created so action created
        else {
            $log = new AuditTrail();
            $log->old_value = '';
            $log->new_value = '';
            $log->action = 'CREATE';
            $log->model = get_class($this->owner->auditModel);
            $log->model_id = $this->owner->auditModel->getPrimaryKey();
            $log->field = 'N/A';
            $log->created = date('Y-m-d H:i:s');
            $log->user_id = $userid;
            $log->page_trail_id = $pageTrailId;
            $log->save();

            foreach ($newattributes as $name => $new) {
                if (in_array($name, $this->ignoreFields['create'])) continue;
                if (!$new) continue;
                $log = new AuditTrail;
                $log->old_value = '';
                $log->new_value = $new;
                $log->action = 'SET';
                $log->model = get_class($this->owner->auditModel);
                $log->model_id = $this->owner->auditModel->getPrimaryKey();
                $log->field = $name;
                $log->created = date('Y-m-d H:i:s');
                $log->user_id = $userid;
                $log->page_trail_id = $pageTrailId;
                $log->save();
            }

        }
        parent::afterSave($event);
    }

    /**
     * @param $event
     */
    public function afterDelete($event)
    {

        try {
            $userid = user()->id;
        } catch (Exception $e) {
            $userid = 0;
        }

        $pageTrailId = static_id('page_trail_id');

        // delete
        $log = new AuditTrail;
        $log->old_value = '';
        $log->new_value = '';
        $log->action = 'DELETE';
        $log->model = get_class($this->owner->auditModel);
        $log->model_id = $this->owner->auditModel->getPrimaryKey();
        $log->field = 'N/A';
        $log->created = date('Y-m-d H:i:s');
        $log->user_id = $userid;
        $log->page_trail_id = $pageTrailId;
        $log->save();
        parent::afterDelete($event);
    }

    /**
     * @param $event
     */
    public function afterFind($event)
    {
        // Save old values
        $this->setOldAttributes($this->owner->getAttributes());
        parent::afterFind($event);
    }

    /**
     * @return array
     */
    public function getOldAttributes()
    {
        return $this->_oldattributes;
    }

    /**
     * @param $value
     */
    public function setOldAttributes($value)
    {
        $this->_oldattributes = $value;
    }

    /**
     * @return string
     */
    protected function fieldPrefix()
    {
        if (get_class($this->owner) != get_class($this->owner->auditModel)) {
            return get_class($this->owner) . '.';
        }
    }
}
