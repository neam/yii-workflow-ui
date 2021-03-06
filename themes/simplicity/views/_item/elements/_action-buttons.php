<?php
/** @var Controller|WorkflowUiControllerTrait $this */
/** @var ItemTrait|ActiveRecord|QaStateBehavior $model */
?>
<div class="action-buttons">
    <div class="btn-group">
        <?php if (!$model->isPublishable()): ?>
            <?php $this->widget(
                '\TbButton',
                array(
                    'label' => Yii::t('app', 'Edit'),
                    'color' => $this->action->id === 'edit' ? 'inverse' : null, // TODO: no inverse buttons in BS3 http://getbootstrap.com/css/#buttons
                    'size' => TbHtml::BUTTON_SIZE_MINI,
                    'url' => array(
                        'edit',
                        'id' => $model->{$model->tableSchema->primaryKey},
                        'step' => $model->firstFlowStep(),
                    ),
                    'visible' => Yii::app()->user->checkModelOperationAccess($model, 'Edit'),
                )
            ); ?>
        <?php endif; ?>
        <?php $this->widget(
            '\TbButton',
            array(
                'label' => Yii::t('app', 'View'),
                'color' => $this->action->id === 'view' ? 'inverse' : null, // TODO: no inverse buttons in BS3 http://getbootstrap.com/css/#buttons
                'size' => TbHtml::BUTTON_SIZE_MINI,
                'url' => array(
                    'view',
                    'id' => $model->{$model->tableSchema->primaryKey},
                    'step' => $model->firstFlowStep(),
                ),
                'visible' => Yii::app()->user->checkModelOperationAccess($model, 'View'),
            )
        ); ?>
        <?php $this->widget(
            '\TbButton',
            array(
                'label' => Yii::t('app', 'Preview'),
                'color' => $this->action->id === 'preview' ? 'inverse' : null, // TODO: no inverse buttons in BS3 http://getbootstrap.com/css/#buttons
                'size' => TbHtml::BUTTON_SIZE_MINI,
                'url' => array(
                    'preview',
                    'id' => $model->{$model->tableSchema->primaryKey},
                    'step' => $model->firstFlowStep(),
                ),
                'visible' => Yii::app()->user->checkModelOperationAccess($model, 'Preview'),
            )
        ); ?>
    </div>
    <div class="btn-group">
        <?php /*
        <?php if (!$model->qaState()->draft_saved): ?>
            <?php $this->widget(
                '\TbButton',
                array(
                    'label' => Yii::t('crud', 'Prepare to save'),
                    'color' => $this->action->id == 'draft' ? 'inverse' : null,
                    'icon' => 'glyphicon-pencil' . ($this->action->id == 'draft' ? ' icon-white' : null),
                    'url' => array(
                        'draft',
                        'id' => $model->{$model->tableSchema->primaryKey},
                        'step' => $model->firstFlowStep(),
                    )
                )
            ); ?>
        <?php endif; ?>
        */ ?>
        <?php if ((!$model->isPublishable() && !$model->isPublished())): ?>
            <?php $this->widget(
                '\TbButton',
                array(
                    'label' => Yii::t('app', 'Prepare for Review'),
                    'color' => $this->action->id === 'prepareForReview' ? 'inverse' : null, // TODO: no inverse buttons in BS3 http://getbootstrap.com/css/#buttons
                    'size' => TbHtml::BUTTON_SIZE_MINI,
                    'url' => array(
                        'prepareForReview',
                        'id' => $model->{$model->tableSchema->primaryKey},
                        'step' => $model->firstFlowStep(),
                    ),
                    'visible' => Yii::app()->user->checkModelOperationAccess($model, 'PrepareForReview'),
                )
            ); ?>
        <?php else: ?>
            <?php // TODO: Action to prevent review. ?>
        <?php endif; ?>
        <?php if (!$model->isPublishable() && !$model->isPublished()): ?>
            <?php $this->widget(
                '\TbButton',
                array(
                    'label' => Yii::t('app', 'Prepare for Publishing'),
                    'color' => $this->action->id === 'prepareForPublishing' ? 'inverse' : null,
                    'size' => TbHtml::BUTTON_SIZE_MINI,
                    'url' => array(
                        'prepareForPublishing',
                        'id' => $model->{$model->tableSchema->primaryKey},
                        'step' => $model->firstFlowStep(),
                    ),
                    'visible' => Yii::app()->user->checkModelOperationAccess($model, 'PrepareForPublishing'),
                )
            ); ?>
        <?php else: ?>
            <?php // TODO: Action to prevent publishing. ?>
        <?php endif; ?>
    </div>
    <div class="btn-group">
        <?php $this->widget(
            '\TbButton',
            array(
                'label' => Yii::t('crud', 'Evaluate'),
                'color' => $this->action->id === 'evaluate' ? 'inverse' : null,
                'size' => TbHtml::BUTTON_SIZE_MINI,
                'url' => array(
                    'evaluate',
                    'id' => $model->{$model->tableSchema->primaryKey},
                    'step' => $model->firstFlowStep(),
                ),
                'visible' => Yii::app()->user->checkModelOperationAccess($model, 'Evaluate'),
            )
        ); ?>
        <?php /* $this->widget(
            '\TbButton',
            array(
                'label' => Yii::t('model', 'Proofread'),
                'color' => $this->action->id === 'proofread' ? 'inverse' : null,
                'size' => TbHtml::BUTTON_SIZE_MINI,
                'url' => array(
                    'proofRead',
                    'id' => $model->{$model->tableSchema->primaryKey},
                ),
                'visible' => Yii::app()->user->checkModelOperationAccess($model, 'Proofread'),
            )
        ); */ ?>
        <?php $a = $model->getCurrentlyTranslatableAttributes(); ?>
        <?php if (!empty($a)): ?>
            <?php $this->widget(
                '\TbButton',
                array(
                    'label' => Yii::t('model', 'Translate'),
                    'color' => $this->action->id === 'translationOverview' ? 'inverse' : null,
                    'size' => TbHtml::BUTTON_SIZE_MINI,
                    'url' => array(
                        'translationOverview',
                        'id' => $model->{$model->tableSchema->primaryKey},
                    ),
                    'visible' => Yii::app()->user->checkModelOperationAccess($model, 'Translate'),
                )
            ); ?>
        <?php else: ?>
            <?php echo TbHtml::linkButton(
                    Yii::t('model', 'Translate'),
                    array(
                        'disabled' => true,
                        'size' => TbHtml::BUTTON_SIZE_MINI,
                    )
                ); ?>
        <?php endif; ?>
    </div>
    <div class="btn-group">
        <?php if (Yii::app()->user->checkAccess('Publish')): ?>
            <?php if ($model->isUnpublishable()): ?>
                <?php $this->widget(
                    '\TbButton',
                    array(
                        'label' => Yii::t('model', 'Unpublish'),
                        'size' => TbHtml::BUTTON_SIZE_MINI,
                        'url' => array(
                            'unpublish',
                            'id' => $model->{$model->tableSchema->primaryKey},
                        ),
                        'visible' => Yii::app()->user->checkModelOperationAccess($model, 'Publish'),
                    )
                ); ?>
            <?php elseif ($model->isPublishable()): ?>
                <?php $this->widget(
                    '\TbButton',
                    array(
                        'label' => Yii::t('model', 'Publish'),
                        'size' => TbHtml::BUTTON_SIZE_MINI,
                        'url' => array(
                            'publish',
                            'id' => $model->{$model->tableSchema->primaryKey},
                        ),
                        'visible' => Yii::app()->user->checkModelOperationAccess($model, 'Publish'),
                    )
                ); ?>
            <?php else: ?>
                <?php echo TbHtml::linkButton(
                    Yii::t('model', 'Publish'),
                    array(
                        'disabled' => true,
                        'size' => TbHtml::BUTTON_SIZE_MINI,
                    )
                ); ?>
            <?php endif; ?>
        <?php endif; ?>
    </div>
    <div class="btn-group">
        <?php $this->widget(
            '\TbButton',
            array(
                'label' => Yii::t('model', 'Clone'),
                'color' => $this->action->id === 'clone' ? 'inverse' : null,
                'size' => TbHtml::BUTTON_SIZE_MINI,
                'url' => array(
                    'clone',
                    'id' => $model->{$model->tableSchema->primaryKey},
                ),
                'visible' => Yii::app()->user->checkModelOperationAccess($model, 'Clone'),
            )
        ); ?>
        <?php $this->widget(
            '\TbButton', array(
                'label' => Yii::t('model', 'Remove'),
                'color' => $this->action->id === 'remove' ? 'inverse' : null,
                'size' => TbHtml::BUTTON_SIZE_MINI,
                'url' => array(
                    'remove',
                    'id' => $model->{$model->tableSchema->primaryKey},
                ),
                'visible' => Yii::app()->user->checkModelOperationAccess($model, 'Remove'),
            )
        ); ?>
    </div>
    <?php
        if (in_array(get_class($model), array_keys(ItemTypes::where('is_access_restricted')))):
    ?>
    <div class="btn-group">
        <?php foreach (Yii::app()->user->getGroups() as $groupId => $groupName): ?>
            <?php if ($model->belongsToGroup($groupName)): ?>
                <?php echo TbHtml::linkButton(
                    $groupName,
                    array(
                        'icon' => TbHtml::ICON_MINUS,
                        'size' => TbHtml::BUTTON_SIZE_MINI,
                        'url' => array(
                            'removeFromGroup',
                            'nodeId' => $model->ensureNode()->id,
                            'modelId' => $model->id,
                            'group' => $groupName,
                            'returnUrl' => Yii::app()->request->url,
                        ),
                    )
                ); ?>
            <?php else: ?>
                <?php echo TbHtml::linkButton(
                    $groupName,
                    array(
                        'icon' => TbHtml::ICON_PLUS,
                        'size' => TbHtml::BUTTON_SIZE_MINI,
                        'url' => array(
                            'addToGroup',
                            'nodeId' => $model->ensureNode()->id,
                            'modelId' => $model->id,
                            'group' => $groupName,
                            'returnUrl' => Yii::app()->request->url,
                        ),
                    )
                ); ?>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php
    endif;
    ?>
</div>
