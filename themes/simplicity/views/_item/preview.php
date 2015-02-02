<?php
/** @var Controller|WorkflowUiControllerTrait $this */
/** @var ActiveRecord|ItemTrait $model */
?>
<?php $workflowCaption = Yii::t('app', 'Preview'); ?>
<?php $this->setPageTitle(Yii::t('model', $this->modelClass) . ' - ' . $workflowCaption); ?>
<?php $this->renderPartial(
    '_preview',
    array(
        'preview' => true,
        'data' => $model,
    )
); ?>
<?php if ($this->showBackToTranslationButton()): ?>
    <?php echo TbHtml::linkButton(
        Yii::t('app', 'Go Back'),
        array(
            'size' => TbHtml::BUTTON_SIZE_SM,
            'url' => $this->getBackToTranslationUrl(),
        )
    ); ?>
<?php endif; ?>
