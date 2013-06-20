<?php
/**
 * Override CActiveDataProvider
 */
class ActiveDataProvider extends CActiveDataProvider
{
    public function getSort()
    {
        if (($sort = parent::getSort()) !== false) {
            if (!$sort->defaultOrder) {
                $sort->modelClass = $this->modelClass;
                $sort->multiSort = true;
                $pk = $this->model->getMetaData()->tableSchema->primaryKey;
                if ($pk && !is_array($pk)) {
                    $sort->defaultOrder = 't.' . $pk . ' DESC';
                }
            }
        }
        return $sort;
    }
}