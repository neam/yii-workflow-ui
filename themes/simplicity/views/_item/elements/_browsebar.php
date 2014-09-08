<?php
/* @var ActiveRecord $model */

?>
<div class="browsebar">
    <div class="browsebar-actions">
        <?php $this->widget(
            '\TbButton',
            array(
                'label' => Yii::t('model', 'Add'),
                'url' => array('add'),
                'color' => TbHtml::BUTTON_COLOR_PRIMARY,
                'visible' => Yii::app()->user->checkAccess('Add'),
                'htmlOptions' => array('id' => 'addButton'),
            )
        ); ?>
    </div>
</div>