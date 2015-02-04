<?php

$config['import'][] = 'vendor.neam.yii-workflow-ui.widgets.*';
$config['import'][] = 'vendor.neam.yii-workflow-ui.components.AppActiveForm';
$config['import'][] = 'vendor.neam.yii-workflow-ui.helpers.Html';
$config['import'][] = 'vendor.neam.yii-workflow-ui.helpers.WorkflowUiControllerConstants';
$config['import'][] = 'vendor.neam.yii-workflow-ui.traits.WorkflowUiControllerTrait';
$config['import'][] = 'vendor.neam.yii-workflow-ui.traits.GridviewControllerActionsTrait';
$config['import'][] = 'vendor.neam.yii-workflow-ui.components.WorkflowUi';

require($applicationDirectory . '/../vendor/neam/yii-workflow-ui/global.php');

$config['components']['workflowUi'] = array(
    'class' => 'vendor.neam.yii-workflow-ui.components.WorkflowUi',
);

$config['modules']['p3media'] = CMap::mergeArray($config['modules']['p3media'], array(
    'params' => array(
        'presets' => array(
            'item-thumbnail' => array(
                'name' => 'Item Thumbnail',
                'commands' => array(
                    'resize' => array(150, 80, 7), // Image::AUTO
                    'quality' => '85',
                ),
                'type' => 'jpg',
            ),
            'select2-thumb' => array(
                'name' => 'Select2 Thumbnail',
                'commands' => array(
                    'resize' => array(35, 35, 7), // Image::AUTO
                    'quality' => '85',
                ),
                'type' => 'jpg',
            ),
            'item-workflow-preview' => array(
                'name' => 'Item Workflow Preview',
                'commands' => array(
                    'resize' => array(442, 253, 7),
                    'quality' => 85,
                ),
            ),
        ),
    ),
));
