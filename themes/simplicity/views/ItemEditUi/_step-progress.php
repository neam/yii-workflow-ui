<?php
/** @var integer $currentStepNumber */
/** @var integer $totalStepCount */
?>
<div class="step-progress">
    <div class="progress-dots">
        <ul class="step-progress-dots">
            <?php for ($i = 1; $i <= $totalStepCount; $i++): ?>
                    <li class="active">
                        <a href="<?php echo Yii::app()->controller->createUrl('p1campaign/edit',array('id'=> $_GET['id'],'step' => $steps[$i-1]['step'])) ?>">...</a>
                    </li>
            <?php endfor; ?>
        </ul>
    </div>
    <div class="progress-text">
        <?php echo Yii::t(
            'app', 'Step {currentStep} of {totalSteps}',
            array(
                '{currentStep}' => $currentStepNumber,
                '{totalSteps}' => $totalStepCount,
            )
        ); ?>
    </div>
</div>
