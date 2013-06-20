<?php
/**
 * SoftDeleteBehavior
 *
 * @package app.model.behavior
 * @author Brett O'Donnell <brett@mrphp.com.au>
 */
class SoftDeleteBehavior extends CActiveRecordBehavior
{
    /**
     * @var string
     */
    public $deleted = 'deleted';
    /**
     * @var string
     */
    public $deletedBy = 'deleted_by';

    /**
     * @param $event
     */
    public function beforeDelete($event)
    {
        if (isset($this->Owner->tableSchema->columns[$this->deleted])) {
            $this->Owner->{$this->deleted} = date('Y-m-d H:i:s');
        }
        if (isset($this->Owner->tableSchema->columns[$this->deletedBy])) {
            $this->Owner->{$this->deletedBy} = Yii::app()->user->id;
        }
        $this->Owner->save(false);

        //prevent real deletion
        $event->isValid = false;
    }

    /**
     * @return mixed
     * @throws CDbException
     */
    public function undelete()
    {
        if (!$this->Owner->getIsNewRecord()) {
            //Yii::trace(get_class($this) . '.undelete()', 'system.db.ar.CActiveRecord');
            $updateFields = array(
                $this->deleted => null,
            );
            return $this->Owner->updateByPk($this->Owner->getPrimaryKey(), $updateFields);
        }
        else
            throw new CDbException(Yii::t('yii', 'The active record cannot be undeleted because it is new.'));
    }

    /**
     * @return mixed
     */
    public function deleteds()
    {
        $this->Owner->getDbCriteria->mergeWith(array(
            'condition' => $this->deleted . ' IS NOT NULL'
        ));
        return $this->Owner;
    }

    /**
     * @return mixed
     */
    public function notdeleteds()
    {
        $this->Owner->getDbCriteria->mergeWith(array(
            'condition' => $this->deleted . ' IS NULL'
        ));
        return $this->Owner;
    }

}