<?php
/**
 * @var $this ErrorController
 * @var $errors array
 */
$this->pageTitle = t('Errors');
$this->pageHeading = t('Errors');
$this->breadcrumbs = array(t('Errors'));
?>

<?php echo l(t('Clear Errors'), array('/error/clear')); ?>

<table class="table table-bordered table-striped table-condensed">
    <tr>
        <td><?php echo t('Error'); ?></td>
        <td><?php echo t('PageTrail'); ?></td>
        <td><?php echo t('Route'); ?></td>
        <td><?php echo t('Date'); ?></td>
    </tr>
    <?php
    foreach ($errors as $error) {
        $pageTrailId = str_replace(array('archive/', 'pt-', 'pagetrail-', '.html'), '', $error);
        $pageTrailLink = '';
        $pageTrailCreated = '';
        $pageTrailRoute = '';
        $errorLink = array('/error/view', 'error' => $error);
        if (strpos($error, 'archive/') !== false) {
            $errorLink = array('/error/view', 'error' => str_replace('archive/', '', $error), 'archive' => 1);
        }
        if ($pageTrailId && is_numeric($pageTrailId)) {
            $pageTrail = PageTrail::model()->findByPk($pageTrailId);
            if ($pageTrail) {
                $pageTrailLink = $pageTrail->getLink();
                $pageTrailCreated = $pageTrail->created;
                $pageTrailCreated = Time::agoIcon($pageTrailCreated);
                $pageTrailRoute = $pageTrail->link;
                $pageTrailRoute = str_replace(bu(), '', $pageTrailRoute);
                $pageTrailRoute = str_replace($_SERVER['HTTP_HOST'], '', $pageTrailRoute);
                $pageTrailRoute = str_replace('http://', '', $pageTrailRoute);
                $pageTrailRoute = StringHelper::getFirstLineWithIcon($pageTrailRoute, 60);
            }
        }
        ?>
        <tr>
            <td><?php echo l($error, $errorLink); ?></td>
            <td><?php echo $pageTrailLink; ?></td>
            <td><?php echo $pageTrailRoute; ?></td>
            <td><?php echo $pageTrailCreated; ?></td>
        </tr>
    <?php
    }
    ?>
</table>