<?php /* @var ItemEditUi $this */ ?>
<?php $this->form = $this->beginWidget(
    'AppActiveForm',
    array(
        'action' => $this->getFormAction(),
        'id' => 'item-form',
        'enableAjaxValidation' => true,
        'clientOptions' => array( //'validateOnSubmit' => true,
        ),
        'htmlOptions' => array(
            'class' => 'dirtyforms',
            'enctype' => 'multipart/form-data',
        ),
    )
); ?>
<input type="hidden" name="form-url" value="<?php echo CHtml::encode(Yii::app()->request->url); ?>"/>
<?php /*
    <div class="item-header">
        <div class="header-text">
            <h1 class="item-heading"><?php echo $this->model->itemLabel; ?></h1>
        </div>
    </div>
    */
?>

<div class="item-top-actions">
    <?php $this->widget(
        '\TbButton',
        array(
            'color' => TbHtml::BUTTON_COLOR_LINK,
            'label' => Yii::t('model', 'Reset'),
            'url' => Yii::app()->request->url,
            'htmlOptions' => array(
                'class' => 'btn-dirtyforms ignoredirty',
            ),
        )
    ); ?>
    <?php echo TbHtml::submitButton(
        Yii::t('model', 'Save changes'),
        array(
            'class' => 'btn-dirtyforms preview-button',
            'color' => TbHtml::BUTTON_COLOR_PRIMARY,
            'name' => 'save-changes',
        )
    ); ?>
    <?php echo TbHtml::linkbutton(
        Yii::t('app', 'View'),
        array(
            'class' => 'preview-button',
            'url' => array(
                'view',
                'id' => $this->model->{$this->model->tableSchema->primaryKey},
                'editingUrl' => Yii::app()->request->url,
            ),
            'visible' => Yii::app()->user->checkModelOperationAccess($model, 'View'),
        )
    ); ?>
    <?php echo TbHtml::linkbutton(
        Yii::t('app', 'Preview'),
        array(
            'class' => 'preview-button',
            'url' => array(
                'preview',
                'id' => $this->model->{$this->model->tableSchema->primaryKey},
                'editingUrl' => Yii::app()->request->url,
            ),
            'visible' => Yii::app()->user->checkModelOperationAccess($model, 'Preview'),
        )
    ); ?>

</div>

<?php
$step = $_GET['step'];
$stepCaption = $this->controller->workflowData['caption'];
?>
<h2 class="form-title"><?php echo $this->controller->workflowData['caption']; ?></h2>
<div class="alerts">
    <div class="alerts-content">
        <?php $this->widget('\TbAlert'); ?>
    </div>
</div>

<?php $this->controller->renderPartial('vendor.neam.yii-workflow-ui.themes.simplicity.views.ItemEditUi._flowbar', compact('model')); ?>

<?php if (in_array($this->actionPartialView, array('_prepareForPublishing', '_prepareForReview'))): ?>
    <?php if (in_array($this->actionPartialView, array('_prepareForPublishing', '_prepareForReview'))): ?>
        <?php $this->render('vendor.neam.yii-workflow-ui.themes.simplicity.views.ItemEditUi._required-workflow', array(
            'model' => $this->model,
        )); ?>
    <?php endif; ?>
    <?php $this->render('vendor.neam.yii-workflow-ui.themes.simplicity.views.ItemEditUi.actions._edit'); ?>
<?php else: ?>
    <?php $this->render("vendor.neam.yii-workflow-ui.themes.simplicity.views.ItemEditUi.actions.$this->actionPartialView"); ?>
<?php endif; ?>

<div class="item-content">
    <div class="item-progress">
        <h2 class="form-title"><?php echo Yii::t('app', 'Steps overview'); ?></h2>
        <?php foreach ($this->controller->workflowData["stepActions"] as $action): ?>
            <?php $this->controller->renderPartial("vendor.neam.yii-workflow-ui.themes.simplicity.views._item.edit/_progress-item", $action); ?>
        <?php endforeach; ?>
        <!--// todo: remove if unused-->
        <!--
        <div class="well well-white">
            <?php //echo $this->renderPartial('vendor.neam.yii-workflow-ui.themes.simplicity.views._item.elements/actions', compact("model", "execution")); ?>
        </div>
        -->
    </div>
</div>

<?php $this->endWidget(); ?>
<?php // Include previously rendered content for modals. ?>
<?php // These need to be rendered outside the <form> since they contain form elements of their own. ?>
<?php foreach (array_reverse($this->controller->clips->toArray(), true) as $key => $clip): // Reverse order for recursive modals to render properly ?>
    <?php if (strpos($key, "modal:") === 0): ?>
        <?php echo $clip; ?>
    <?php endif; ?>
<?php endforeach; ?>
