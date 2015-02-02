<?php
/* @var Composition|ItemTrait $model */
/* @var CompositionController|WorkflowUiControllerTrait $this */
?>
<div class="<?php echo $this->getCssClasses($model); ?>">
    <h1>
        <?php echo $model->itemLabel; ?>
        <?php if ($this->actionIsEvaluate()): ?>
            <small><?php echo $this->getViewActionLabel(); ?></small>
        <?php endif; ?>
    </h1>
    <div class="admin-container hide">
        <div class="btn-toolbar">
            <div class="btn-group">
                <?php $this->widget(
                    '\TbButton',
                    array(
                        'label' => Yii::t('model', 'Manage'),
                        'icon' => 'glyphicon-edit',
                        'url' => array('admin')
                    )
                ); ?>
                <?php $this->widget(
                    '\TbButton',
                    array(
                        'label' => Yii::t('model', 'Edit'),
                        'icon' => 'glyphicon-edit',
                        'url' => array('continueAuthoring', 'id' => $model->{$model->tableSchema->primaryKey})
                    )
                ); ?>
                <?php $this->widget(
                    '\TbButton',
                    array(
                        'label' => Yii::t('model', 'Update'),
                        'icon' => 'glyphicon-edit',
                        'url' => array('update', 'id' => $model->{$model->tableSchema->primaryKey})
                    )
                ); ?>
                <?php $this->widget(
                    '\TbButton',
                    array(
                        'label' => Yii::t('model', 'Delete'),
                        'color' => TbHtml::BUTTON_COLOR_DANGER,
                        'icon' => 'glyphicon-remove icon-white',
                        'htmlOptions' => array(
                            'submit' => array(
                                'delete',
                                'id' => $model->{$model->tableSchema->primaryKey},
                                'returnUrl' => Yii::app()->request->getParam('returnUrl')
                                        ? Yii::app()->request->getParam('returnUrl')
                                        : $this->createUrl('admin')),
                            'confirm' => Yii::t('model', 'Do you want to delete this item?')
                        ),
                    )
                ); ?>
            </div>
        </div>
    </div>
    <?php $this->renderPartial(
        $detailedView,
        array(
            'data' => $model,
        )
    ); ?>
</div>