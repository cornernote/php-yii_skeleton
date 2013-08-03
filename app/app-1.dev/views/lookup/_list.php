<?php
/**
 * @var $this LookupController
 * @var $lookup Lookup
 */

// list
$this->widget('ListView', array(
    'id' => 'lookup-list',
    'dataProvider' => $lookup->search(),
    'itemView' => '_list_view',
));
