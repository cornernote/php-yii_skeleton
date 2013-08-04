<?php
/**
 * --- BEGIN AutoGenerated by tool/generateProperties ---
 *
 * This is the model class for table 'menu'
 *
 * @method Menu with() with()
 * @method Menu find() find($condition, array $params = array())
 * @method Menu[] findAll() findAll($condition = '', array $params = array())
 * @method Menu findByPk() findByPk($pk, $condition = '', array $params = array())
 * @method Menu[] findAllByPk() findAllByPk($pk, $condition = '', array $params = array())
 * @method Menu findByAttributes() findByAttributes(array $attributes, $condition = '', array $params = array())
 * @method Menu[] findAllByAttributes() findAllByAttributes(array $attributes, $condition = '', array $params = array())
 * @method Menu findBySql() findBySql($sql, array $params = array())
 * @method Menu[] findAllBySql() findAllBySql($sql, array $params = array())
 *
 * Methods from behavior AuditBehavior
 * @method afterSave() afterSave(CModelEvent $event)
 * @method afterDelete() afterDelete(CModelEvent $event)
 *
 * Methods from behavior CTimestampBehavior
 * @method beforeSave() beforeSave(CModelEvent $event)
 *
 * Methods from behavior SoftDeleteBehavior
 * @method beforeDelete() beforeDelete(CModelEvent $event)
 * @method undelete() undelete()
 * @method deleteds() deleteds()
 * @method notdeleteds() notdeleteds()
 *
 * Properties from relation
 * @property Menu[] $child
 * @property Menu $parent
 *
 * Properties from table fields
 * @property integer $id
 * @property integer $parent_id
 * @property string $label
 * @property string $icon
 * @property string $url
 * @property string $url_params
 * @property string $target
 * @property string $access_role
 * @property datetime $created
 * @property datetime $deleted
 * @property integer $sort_order
 * @property integer $enabled
 *
 * --- END AutoGenerated by tool/generateProperties ---
 */

class Menu extends ActiveRecord
{

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Menu the static model class
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
        return 'menu';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        $rules = array();
        if ($this->scenario == 'search') {
            $rules[] = array('parent_id, label, icon, url, url_params, target, created, modified, deleted, sort_order, enabled, access_role', 'safe');
        }
        if (in_array($this->scenario, array('create', 'update'))) {
            $rules[] = array('label', 'required');
            $rules[] = array('access_role', 'safe');
            $rules[] = array('parent_id, enabled', 'numerical', 'integerOnly' => true);
            $rules[] = array('label, icon, url, url_params, target', 'length', 'max' => 255);
        }
        return $rules;
    }

    /**
     * @return array containing model behaviors
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
            'child' => array(
                self::HAS_MANY,
                'Menu',
                'parent_id',
                'condition' => 'child.enabled=1 AND child.deleted IS NULL',
                'order' => 'sort_order ASC, label ASC',
            ),
            'parent' => array(
                self::BELONGS_TO,
                'Menu',
                'parent_id',
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
            'parent_id' => t('Parent'),
            'label' => t('Label'),
            'icon' => t('Icon'),
            'url' => t('Url'),
            'url_params' => t('Url Params'),
            'target' => t('Target'),
            'access_role' => t('Access Role'),
            'created' => t('Created'),
            'deleted' => t('Deleted'),
            'sort_order' => t('Sort Order'),
            'enabled' => t('Enabled'),
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     * @param array $options
     * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
     */
    public function search($options = array())
    {
        $criteria = new CDbCriteria;
        $criteria->compare('t.id', $this->id);
        $criteria->compare('t.label', $this->label, true);
        if ($this->parent_id === 0) {
            $criteria->addCondition('t.parent_id IS NULL');
        }
        elseif ($this->parent_id) {
            $criteria->compare('t.parent_id', $this->parent_id);
        }

        if ($this->deleted == 'deleted') {
            $criteria->addCondition('t.deleted IS NOT NULL');
        }
        else {
            $criteria->addCondition('t.deleted IS NULL');
        }

        // allow $options to change the defaultOrder
        $options = CMap::mergeArray(array(
            'defaultOrder' => 'sort_order ASC, label ASC, t.id DESC',
        ), $options);
        $defaultOrder = $options['defaultOrder'];
        unset($options['defaultOrder']);

        // return the DataProvider
        return new ActiveDataProvider($this, CMap::mergeArray(array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => $defaultOrder,
            ),
        ), $options));
    }

    /**
     * Retrieves a list of links to be used in grid and menus.
     * @param bool $extra
     * @return array
     */
    public function getDropdownLinkItems($extra = false)
    {
        $links = array();
        $links[] = array('label' => t('Update'), 'url' => $this->getUrl('update'));
        if ($extra) {
            $more = array();
            $more[] = array('label' => t('Log'), 'url' => $this->getUrl('log'));
            if (!$this->deleted)
                $more[] = array('label' => t('Delete'), 'url' => $this->getUrl('delete', array('returnUrl' => ReturnUrl::getLinkValue(true))), 'linkOptions' => array('data-toggle' => 'modal-remote'));
            else
                $more[] = array('label' => t('Undelete'), 'url' => $this->getUrl('delete', array('task' => 'undelete', 'returnUrl' => ReturnUrl::getLinkValue(true))), 'linkOptions' => array('data-toggle' => 'modal-remote'));
            $links[] = array(
                'label' => t('More'),
                'items' => $more,
            );
        }
        return $links;
    }

    /**
     * @return array|string
     */
    public function getMenuUrl()
    {
        if (strpos($this->url, 'http://') === 0) {
            return $this->url;
        }
        if (strpos($this->url, 'https://') === 0) {
            return $this->url;
        }
        if (strpos($this->url, 'javascript:') === 0) {
            return $this->url;
        }
        $urlParams = array($this->url);
        $params = explode('&', $this->url_params);
        foreach ($params as $param) {
            $param = explode('=', $param);
            if (isset($param[1])) {
                if ($param[1] == '{returnUrl}') {
                    $param[1] = ReturnUrl::getLinkValue(true);
                }
                $urlParams[$param[0]] = $param[1];
            }
        }
        return $urlParams;
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        if (strpos($this->url, '/index') !== false && !$this->url_params) {
            return (app()->controller->getId() == substr($this->url, 1, -6));
        }
        return false;
    }

    /**
     * @param $label
     * @param array $options
     * @return array
     */
    static public function getItemsFromMenu($label, $options = array())
    {
        $menu = Menu::model()->findByAttributes(array('label' => $label));
        if ($menu) {
            return $menu->getItems($options);
        }
        return array();
    }

    /**
     * @param array $options
     * @return array
     */
    public function getItems($options = array())
    {
        $items = array();
        foreach ($this->child as $menu) {
            $itemOptions = isset($options['itemOptions']) ? $options['itemOptions'] : array();
            $linkOptions = isset($options['linkOptions']) ? $options['linkOptions'] : array();
            if ($menu->target) {
                $linkOptions['target'] = $menu->target;
            }
            $submenuOptions = isset($options['submenuOptions']) ? $options['submenuOptions'] : array();
            if (isset($options['submenuOptions'])) {
                unset($options['submenuOptions']);
            }
            $childItems = $menu->getItems($options);

            $item = array();
            if ($menu->access_role) {
                if ($menu->access_role == '?' && !user()->isGuest) {
                    continue;
                }
                if ($menu->access_role == '@' && user()->isGuest) {
                    continue;
                }
                if (!user()->checkAccess($menu->access_role)) {
                    continue;
                }
            }
            if ($menu->label == '---') {
                $item = '---';
            }
            else {
                $item['label'] = $menu->label;
                if ($menu->url) {
                    $item['url'] = $menu->getMenuUrl();
                }
                if ($menu->icon) {
                    $item['icon'] = $menu->icon;
                }
                if ($menu->isActive()) {
                    $item['active'] = true;
                }
                if ($itemOptions) {
                    $item['itemOptions'] = $itemOptions;
                }
                if ($linkOptions) {
                    $item['linkOptions'] = $linkOptions;
                }
                if ($submenuOptions) {
                    $item['submenuOptions'] = $submenuOptions;
                }
                if ($childItems) {
                    $item['items'] = $childItems;
                }
            }
            $items[] = $item;
        }
        return $items;
    }

    /**
     * Get DropDown data, eg
     * echo $form->dropDownListRow($menu, 'parent_id', Menu::model()->getDropDown())
     *
     * @param int $parent_id
     * @param null $condition
     * @return Menu[]
     */
    public function getDropDown($parent_id = 0, $condition = null)
    {
        static $dropdown = array();
        if (isset($dropdown[$parent_id]) && $dropdown[$parent_id]) {
            return $dropdown[$parent_id];
        }
        $menus = array();
        $criteria = new CDbCriteria(array(
            'condition' => 'parent_id=:parent_id' . ($condition ? ' AND (' . $condition . ')' : ''),
            'params' => array('parent_id' => $parent_id),
            'order' => 'sort_order, label',
        ));
        $_menus = $this->findAll($criteria);
        foreach ($_menus as $menu) {
            $menus[] = $menu;
            foreach ($menu->child as $child) {
                $child->label = $menu->label . ' > ' . $child->label;
            }
            $menus = array_merge($menus, $menu->child);
        }
        $dropdown[$parent_id] = CHtml::listData($menus, 'id', 'label');
        return $dropdown[$parent_id];
    }

    /**
     * @param Menu[] $menus
     * @return Menu[]
     */
    public function breadcrumb($menus = array())
    {
        if ($this->parent && $this->parent_id != $this->id && !in_array($this->parent_id, array_keys($menus))) {
            $menus = $this->parent->breadcrumb($menus);
        }
        $menus[$this->id] = $this;
        return $menus;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->label;
    }

}

