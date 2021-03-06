<?php
/* @var Controller|WorkflowUiControllerTrait $this */
/* @var ActiveRecord|ItemTrait $model */
/* @var AppActiveForm $form */
/* @var array $requiredCounts */
?>
<?php $this->beginWidget('Flowbar'); ?>
    <div class="flowbar-head">
        <div class="item-header">
            <div class="header-text">
                <h1 class="header-title">
                    <?php echo $model->itemLabel; ?>
                    <small class="header-icon"><?php echo $this->itemDescriptionTooltip(); ?></small>
                    <small class="header-version">
                        <?php echo Yii::t('app', 'Version') ?>: <?php echo !isset($model->version) ? 'N/A' : $model->version; ?>
                    </small>
                    <small class="header-status">
                        <?php echo Yii::t('app', 'Status'); ?>: <?php echo !isset($model->{$model->_getQaStateAttribute()}) ? 'N/A' : Yii::t(
                            'statuses',
                            $model->qaStateBehavior()->statusLabel
                        ); ?>
                    </small>
                </h1>
            </div>
            <div class="header-actions">
                <div class="btn-group">
                    <?php if (false) $this->widget(
                        '\TbButton',
                        array(
                            'label' => Yii::t('model', 'Preview'),
                            'color' => TbHtml::BUTTON_COLOR_DEFAULT,
                            'url' => array(
                                'preview',
                                'id' => $model->{$model->tableSchema->primaryKey},
                                'editingUrl' => Yii::app()->request->url,
                            ),
                            'visible' => Yii::app()->user->checkModelOperationAccess($model, 'Preview'),
                        )
                    ); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="flowbar-content">
        <?php $this->renderPartial('vendor.neam.yii-workflow-ui.themes.simplicity.views._item.elements/_action-buttons', compact('model')); ?>
    </div>
    <div class="flowbar-foot">
        <div class="foot-text">
            <?php
            // TODO: Ensure $requireCounts is always passed to this view.
            $this->renderPartial('vendor.neam.yii-workflow-ui.themes.simplicity.views._item.elements/_required-counts', compact('model'));
            ?>
        </div>
        <div class="foot-actions">
        </div>
    </div>
<?php $this->endWidget(); ?>
