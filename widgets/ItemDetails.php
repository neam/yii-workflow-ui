<?php

/**
 * ItemDetails class.
 */
class ItemDetails extends CWidget
{
    /**
     * @var ActiveRecord
     */
    public $model;
    /**
     * @var array a list of model attributes to be rendered.
     */
    public $attributes = array();

    /**
     * Initializes the widget.
     * @throws CException if the class and model type are invalid.
     */
    public function init()
    {
        parent::init();

        if (!$this->model instanceof ActiveRecord && in_array('ItemTrait', class_uses($this->model))) {
            throw new CException(Yii::t('error', 'Invalid class: {class}', array(
                '{class}' => get_class($this->model),
            )));
        }
    }

    /**
     * Renders the widget.
     */
    public function run()
    {
        parent::run();
        $this->render('view');
    }

    /**
     * Returns the value of the given attribute.
     * @param string $attribute
     * @return string
     * @throws CException if the model attribute does not exist.
     */
    public function getAttributeValue($attribute)
    {
        return $this->model->hasAttribute($attribute)
            ? e($this->model->{$attribute})
            : '';
    }

    /**
     * Returns the label of the given attribute.
     * @param string $attribute
     * @return string
     * @throws CException if the model attribute does not exist.
     */
    public function getAttributeLabel($attribute)
    {
        return $this->model->hasAttribute($attribute)
            ? e($this->model->getAttributeLabel($attribute))
            : '';
    }
}
