<?php if (!Yii::app()->request->isAjaxRequest) $this->beginContent('//layouts/main'); ?>
<div class="container-fluid">
    <?php $this->renderPartial('//layouts/_content', array('content' => $content)); ?>
</div>
<?php if (!Yii::app()->request->isAjaxRequest) $this->endContent(); ?>