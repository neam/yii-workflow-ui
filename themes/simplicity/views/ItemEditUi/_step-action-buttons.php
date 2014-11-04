<?php /* @var ItemEditUi $this */ ?>
<div class="step-action-buttons">
    <?php /*
    <?php echo TbHtml::linkButton(
        Yii::t('model', 'Reset'),
        array(
            'color' => TbHtml::BUTTON_COLOR_LINK,
            'url' => Yii::app()->request->url,
            'class' => 'btn-dirtyforms ignoredirty',
        )
    ); ?>
    */ ?>
    <?php echo TbHtml::linkButton(
        Yii::t('app', 'Cancel'),
        array(
            'color' => TbHtml::BUTTON_COLOR_LINK,
            'url' => array('dashboard/index'),
            'class' => 'ignoredirty',
        )
    ); ?>
    <?php echo TbHtml::submitButton(
        $this->getSubmitDraftSaveButton(),
        array(
            //'class' => 'btn-dirtyforms',
            'color' => TbHtml::BUTTON_COLOR_PRIMARY,
            'name' => 'save-draft',
        )
    ); ?>
    <?php if (!$this->isFirstStep()): ?>
        <?php echo TbHtml::linkButton(
            Yii::t('app', 'Previous Step'),
            array(
                'color' => TbHtml::BUTTON_COLOR_LINK,
                'url' => $this->createPreviousStepUrl(),
                //'class' => 'btn-dirtyforms ignoredirty',
            )
        ); ?>
    <?php endif; ?>
    <?php echo TbHtml::submitButton(
        $this->getSubmitButtonLabel(),
        array(
            //'class' => 'btn-dirtyforms',
            'color' => TbHtml::BUTTON_COLOR_PRIMARY,
            'name' => 'save-changes',
        )
    ); ?>

</div>
