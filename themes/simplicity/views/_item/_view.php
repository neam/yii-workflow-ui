<?php
/* @var ItemController|WorkflowUiControllerTrait $this */
/* @var Item|ItemTrait $data */
$controllerId = lcfirst($this->modelClass);
?>
<div class="admin-container">
    <div class="btn-toolbar">
        <div class="btn-group">
            <?php if (Yii::app()->controller->checkModelOperationAccessById($data->id, 'Edit')): ?>
                <?php echo CHtml::link( /*'<i class="glyphicon-edit"></i> ' .*/
                    Yii::t('model', 'Edit {model}', array('{model}' => Yii::t('model', 'Item'))), array($controllerId . '/continueAuthoring', 'id' => $data->id, 'returnUrl' => Yii::app()->request->url), array('class' => 'btn')); ?>
            <?php endif; ?>
        </div>
        <div class="btn-group">
            <?php echo CHtml::link('<i class="glyphicon-view"></i> ' . Yii::t('model', 'View {model} in pages-desktop', array('{model}' => Yii::t('model', 'Item'))), PAGES_DESKTOP_BASE_URL . '/' . /*$data->compositionType->ref . '/' .*/
                $data->node_id, array('class' => 'btn', 'target' => '_blank')); ?>
        </div>
        <div class="btn-group">
            <?php echo CHtml::link('<i class="glyphicon-view"></i> ' . Yii::t('model', 'View {model} in public REST API', array('{model}' => Yii::t('model', 'Item'))), '/api/v1/item/' . $data->node_id, array('class' => 'btn', 'target' => '_blank')); ?>
        </div>
    </div>
</div>
<?php foreach ($data->flowSteps() as $step => $attributes): ?>
    <h2><?php echo $step; ?></h2>
    <?php
    $this->widget(
        'ItemDetails',
        array(
            'model' => $data,
            'attributes' => $attributes,
        )
    );

endforeach;
?>

<h2>metadata</h2>
<?php
$this->widget(
    'ItemDetails',
    array(
        'model' => $data,
        'attributes' => array(
            'node_id',
            'created',
            'modified',
        ),
    )
);
?>

<?php if (isset($data->routes)): ?>

    <h2>Try routes</h2>

    <?php
    foreach ($data->routes as $route):
        ?>
        <div class="btn-group">
            <?php echo CHtml::link('<i class="glyphicon-view"></i> ' . Yii::t('model', 'View {model} in pages-desktop using route {route}', array('{model}' => Yii::t('model', 'Item'), '{route}' => $route->route)), PAGES_DESKTOP_BASE_URL . $route->route, array('class' => 'btn', 'target' => '_blank')); ?>
        </div>
    <?php
    endforeach;
    if (empty($data->routes)) {
        echo Yii::t('app', 'No routes');
    }
    ?>

    <?php if (Yii::app()->user->checkAccess('Developer')): ?>
        <div class="admin-container hide">
            <h3>Developer access</h3>
            <?php echo CHtml::link('<i class="glyphicon-edit"></i> ' . Yii::t('model', 'Update {model}', array('{model}' => Yii::t('model', 'Slideshow File'))), array('profile/update', 'id' => $data->id, 'returnUrl' => Yii::app()->request->url), array('class' => 'btn')); ?>
        </div>
    <?php endif; ?>

<?php endif; ?>
