<?php
/* @var Controller|WorkflowUiControllerTrait $this */
/* @var ActiveRecord|ItemTrait $model */
/* @var CActiveDataProvider $dataProvider */
?>
<?php /*
// TODO: Create a method for setting the menu items, and remove this if statement.
if (empty($this->menu)) {
    $this->menu = array(
        array('label' => Yii::t('app', 'Create'), 'url' => array('create')),
    );
}
*/ ?>
<div class="<?php echo $this->getCssClasses($model); ?>">
    <h1 class="page-title">
        <?php echo Yii::t('model', $model->modelLabel, 2); ?>
        <small><?php echo $this->itemDescriptionTooltip(); ?></small>
    </h1>
    <?php $this->renderPartial('vendor.neam.yii-workflow-ui.themes.simplicity.views._item.elements._browsebar', compact('model')); ?>
    <?php $this->widget(
        '\TbListView',
        array(
            'dataProvider' => $dataProvider,
            'template' => $this->renderPartial('vendor.neam.yii-workflow-ui.themes.simplicity.views._item.listview-template', null, true),
            'itemView' => 'vendor.neam.yii-workflow-ui.themes.simplicity.views._item._list-item',
            'enableHistory' => 'true',
            'pager' => array(
                'class' => '\TbPager',
                'size' => TbHtml::PAGINATION_SIZE_SMALL,
                'hideFirstAndLast' => true,
            ),
        )
    ); ?>
</div>
