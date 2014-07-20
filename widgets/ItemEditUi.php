<?php

class ItemEditUi extends CWidget
{
    const ACTION_TRANSLATE = 'translate';
    const ACTION_EDIT = 'edit';
    const STEP_SUBTITLES = 'subtitles';

    /**
     * @var ActiveRecord|ItemTrait
     */
    public $model;

    /**
     * @var TbActiveForm|AppActiveForm
     */
    public $form;

    /**
     * @var string the controller action ID.
     */
    public $actionId;

    /**
     * @var string
     */
    public $actionPartialView;

    /**
     * @var string
     */
    public $step;

    /**
     * @var string the wrapping CSS class based on the step
     */
    public $cssClass;

    /**
     * Initializes the widget.
     * @throws CException
     */
    public function init()
    {
        parent::init();
        $this->actionId = $this->controller->action->id;
        $this->actionPartialView = $this->getActionPartialViewName();
        $this->cssClass = $this->getCssClass($this->step);
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        parent::run();

        $this->render(
            'vendor.neam.yii-workflow-ui.themes.simplicity.views.ItemEditUi.view',
            array(
                'model' => $this->model,
            )
        );
    }

    /**
     * Returns the wrapper CSS class for the given step.
     * @param string $step
     * @return string
     */
    public function getCssClass($step)
    {
        return "step-$step";
    }

    /**
     * Returns the current action heading text.
     * @return string
     */
    public function getActionHeading()
    {
        $heading = Yii::t('app', 'Editing');

        if ($this->actionId === self::ACTION_TRANSLATE) {
            $heading = Yii::t(
                'app', 'Translating into {language}',
                array(
                    '{language}' => LanguageHelper::getName($this->controller->workflowData['translateInto']),
                )
            );
        }

        return $heading;
    }

    /**
     * Returns the caption of the current step.
     * @return string
     */
    public function getStepCaption()
    {
        $captions = $this->model->flowStepCaptions();
        return $captions[$this->step];
    }

    /**
     * Returns the name of the action-specific partial view.
     * @return string
     */
    public function getActionPartialViewName()
    {
        return '_' . $this->controller->action->id;
    }

    /**
     * Builds and returns the form action URL for the current step.
     * @return string
     */
    public function getFormAction()
    {
        $urlParams = array(
            'returnUrl' => $this->createNextStepUrl(),
        );

        return $this->createFormActionUrlForStep($this->step, $urlParams);
    }

    /**
     * Builds a form action URL for the given step.
     * @param string $step
     * @return string
     * @throws CException
     */
    public function createFormActionUrlForStep($step, $urlParams = array())
    {
        if (isset($this->controller->workflowData['stepActions'])) {
            $stepActions = $this->controller->workflowData['stepActions'];
            $stepAction = null;

            foreach ($stepActions as $action) {
                if ($action['step'] === $step) {
                    $stepAction = $action;
                    continue;
                }
            }

            $modelRoute = lcfirst(get_class($this->model));
            $controllerAction = $stepAction['editAction'];

            $route = "/$modelRoute/$controllerAction";

            $params = array();
            $params['id'] = $this->model->{$this->model->tableSchema->primaryKey};
            $params['step'] = $stepAction['step'];
            if (isset($this->controller->workflowData['translateInto'])) {
                $params['translateInto'] = $this->controller->workflowData['translateInto'];
            }

            // Include supplied URL params (if any)
            if (!empty($urlParams)) {
                foreach ($urlParams as $key => $urlParam) {
                    $params[$key] = $urlParam;
                }
            }

            return Yii::app()->createUrl($route, $params);
        } else {
            throw new CException('Step actions not defined.');
        }
    }

    /**
     * Creates an URL to the next step.
     * @return string
     */
    public function createNextStepUrl()
    {
        $currentStepNumber = $this->getCurrentStepNumber();
        $nextStepIndex = $this->getCurrentStepIndex() + 1;
        $totalStepCount = $this->getTotalStepCount();
        $stepActions = $this->controller->workflowData['stepActions'];

        if ($currentStepNumber < $totalStepCount) {
            $nextStepAction = $stepActions[$nextStepIndex];
            $url = $this->createFormActionUrlForStep($nextStepAction['step']);
        } else {
            $url = Html::normalizeUrl(array('preview', 'id' => $this->model->id));
        }

        return $url;
    }

    /**
     * Creates an URL to the previous step.
     * @return string
     */
    public function createPreviousStepUrl()
    {
        $currentStepNumber = $this->getCurrentStepNumber();
        $previousStepIndex = $this->getCurrentStepIndex() - 1;
        $stepActions = $this->controller->workflowData['stepActions'];

        if ($currentStepNumber > 1) {
            $previousStepAction = $stepActions[$previousStepIndex];
            $url = $this->createFormActionUrlForStep($previousStepAction['step']);
        } else {
            $url = Yii::app()->createUrl('/dashboard/index');
        }

        return $url;
    }

    /**
     * Checks if the current step is the first one.
     * @return boolean
     */
    public function isFirstStep()
    {
        return (int) $this->getCurrentStepNumber() === 1;
    }

    /**
     * Checks if the current step is the final one.
     * @return boolean
     */
    public function isFinalStep()
    {
        return (int) $this->getCurrentStepNumber() === (int) $this->getTotalStepCount();
    }

    /**
     * Returns the text label for the submit button.
     * @return string
     */
    public function getSubmitButtonLabel()
    {
        $subject = $this->controller->action->id === 'translate'
            ? Yii::t('app', 'Translation')
            : Yii::t('app', $this->model->modelLabel, 1);

        return $this->isFinalStep()
            ? Yii::t('app', 'Save {subject}', array('{subject}' => $subject))
            : Yii::t('app', 'Next Step');
    }

    /**
     * Returns the current step index.
     * @return integer
     */
    public function getCurrentStepIndex()
    {
        $currentStep = $this->step;

        if (isset($this->controller->workflowData['stepActions']) && !empty($this->controller->workflowData['stepActions'])) {
            $steps = $this->controller->workflowData['stepActions'];

            foreach ($steps as $index => $step) {
                if ($step['step'] === $currentStep) {
                    return $index;
                }
            }

            throw new CException('Current step ' . $currentStep . ' is not available in the current workflow.');
        } else {
            throw new CException('There are no steps available in the current workflow.');
        }
    }

    /**
     * Returns the current step number.
     * @return integer
     */
    public function getCurrentStepNumber()
    {
        return $this->getCurrentStepIndex() + 1;
    }

    /**
     * Returns the total number of steps.
     * @return integer
     */
    public function getTotalStepCount()
    {
        return isset($this->controller->workflowData['stepActions'])
            ? count($this->controller->workflowData['stepActions'])
            : null;
    }

    /**
     * Returns the total number of steps.
     * @return integer
     */
    public function getCurrentWorkflowProgress()
    {
        return isset($this->controller->workflowData['stepActions'])
            ? $this->controller->workflowData['stepActions'][$this->getCurrentStepIndex()]["workflowProgress"]
            : null;
    }
}
