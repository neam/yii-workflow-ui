<?php

trait WorkflowUiTbActiveFormTrait
{

    /**
     * Creates a Select2 field.
     * @param ActiveRecord $model
     * @param string $attribute
     * @param array $data
     * @param array $htmlOptions
     * @return string
     */
    public function select2($model, $attribute, $data = array(), $htmlOptions = array())
    {
        $htmlOptions = $this->processControlGroupOptions($model, $attribute, $htmlOptions);
        return Html::activeSelect2($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Creates a Select2 control group field.
     * @param ActiveRecord $model
     * @param string $attribute
     * @param array $data
     * @param array $htmlOptions
     * @return string
     */
    public function select2ControlGroup($model, $attribute, $data = array(), $htmlOptions = array())
    {
        $htmlOptions = $this->processControlGroupOptions($model, $attribute, $htmlOptions);
        return Html::activeSelect2ControlGroup($model, $attribute, $data, $htmlOptions);
    }

    /**
     * Creates a multi text field control group for a translatable attribute.
     * @param ActiveRecord|ItemTrait $model
     * @param string $attribute
     * @param string $translateInto
     * @param string $controllerAction
     * @param array $fieldOptions
     * @return string
     */
    public function translateTextFieldControlGroup($model, $attribute, $controllerAction, $fieldOptions = array())
    {
        return $this->translateFieldControlGroup($model, $attribute, $controllerAction, TbHtml::INPUT_TYPE_TEXT, $fieldOptions);
    }

    /**
     * Creates a multi textarea field control group for a translatable attribute.
     * @param ActiveRecord|ItemTrait $model
     * @param string $attribute
     * @param string $translateInto
     * @param string $controllerAction
     * @param array $fieldOptions
     * @return string
     */
    public function translateTextAreaControlGroup($model, $attribute, $controllerAction, $fieldOptions = array())
    {
        return $this->translateFieldControlGroup($model, $attribute, $controllerAction, TbHtml::INPUT_TYPE_TEXTAREA, $fieldOptions);
    }

    /**
     * Creates a multi text field control group for a translatable attribute.
     * @param ActiveRecord|ItemTrait $model
     * @param string $attribute
     * @param string $translateInto
     * @param string $controllerAction
     * @param array $fieldOptions
     * @return string
     */
    public function textFieldControlGroup($model, $attribute, $fieldOptions = array())
    {
        return $this->fieldControlGroup($model, $attribute, TbHtml::INPUT_TYPE_TEXT, $fieldOptions);
    }

    /**
     * Creates a multi textarea field control group for a translatable attribute.
     * @param ActiveRecord|ItemTrait $model
     * @param string $attribute
     * @param string $translateInto
     * @param string $controllerAction
     * @param array $fieldOptions
     * @return string
     */
    public function textAreaControlGroup($model, $attribute, $fieldOptions = array())
    {
        return $this->fieldControlGroup($model, $attribute, TbHtml::INPUT_TYPE_TEXTAREA, $fieldOptions);
    }

    /**
     * Creates an item title text field control group with support for SlugIt auto-generation.
     * @param ActiveRecord $model
     * @param string $attribute
     * @param array $htmlOptions
     * @return string
     */
    public function itemTitleTextFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $titleClass = 'slugit-from-1';
        $slugClass = 'slugit-to-1';

        $classes = array();
        if (isset($htmlOptions['class'])) {
            $classes = explode(' ', $htmlOptions['class']);
        }
        $classes[] = $titleClass;
        $htmlOptions['class'] = implode(' ', $classes);

        Html::jsSlugIt(array(
            ".$titleClass" => ".$slugClass",
        ));

        return parent::textFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Builds a multi input field configuration for a translatable attribute.
     * @param ActiveRecord|ItemTrait $model
     * @param string $attribute
     * @param string $translateInto
     * @param string $inputType
     * @param array $fieldOptions
     * @return string
     */
    public function translateFieldControlGroup($model, $attribute, $translateInto, $inputType, $fieldOptions = array())
    {
        $attributeSourceLanguage = $attribute . '_' . $model->source_language;
        $attributeTranslateInto = $attribute . '_' . $translateInto;

        // TODO: Add support for dynamic htmlOptions.
        $htmlOptions = $this->fieldControlGroupHtmlOptions($attribute);

        // Get hint
        if (isset($fieldOptions['hint']) && $fieldOptions['hint']) {
            $htmlOptions['label'] = Html::attributeLabelWithTooltip($model, $attributeTranslateInto, $attribute);
        } else {
            $htmlOptions['label'] = $model->getAttributeLabel($attributeTranslateInto);
        }

        // Disable translation field if the original value is empty
        if (empty($model->{$attributeSourceLanguage})) {
            $htmlOptions['disabled'] = true;
            return; // do not render at all
        }

        $html = Html::activeStaticTextFieldControlGroup(
            $model,
            $attributeSourceLanguage,
            $htmlOptions
        );

        $html .= $this->createInput(
            $inputType,
            $model->edited(),
            $attributeTranslateInto,
            $htmlOptions
        );

        $errorOptions = TbArray::popValue('errorOptions', $htmlOptions, array());

        if ($model->hasErrors($attributeTranslateInto)) {
            TbHtml::addCssClass('error', $errorOptions);
            $html .= $this->error($model, $attributeTranslateInto, $errorOptions);
        }

        return $html;
    }

    /**
     * @param $model
     * @param $attribute
     * @param $inputType
     * @param array $fieldOptions
     * @return string
     */
    public function fieldControlGroup($model, $attribute, $inputType, $fieldOptions = array())
    {

        if ($model->anyTranslatable(array($attribute))) {
            $attributeSourceLanguage = $attribute . '_' . $model->source_language;
            $originalAttribute = $attribute;
            $attribute = $attributeSourceLanguage;
        } else {
            $originalAttribute = $attribute;
        }

        // TODO: Add support for dynamic htmlOptions.
        $htmlOptions = $this->fieldControlGroupHtmlOptions($originalAttribute);

        // Get hint
        if (isset($fieldOptions['hint']) && $fieldOptions['hint']) {
            $htmlOptions['label'] = Html::attributeLabelWithTooltip($model, $attribute, $originalAttribute);
        }

        // Bind slug field
        Html::jsSlugIt(array(
            '.slugit-from-1' => '.slugit-to-1',
        ));

        return $this->createControlGroup($inputType, $model, $attribute, $htmlOptions);
    }

    public function fieldControlGroupHtmlOptions($originalAttribute)
    {

        // TODO: Add support for dynamic htmlOptions.
        $htmlOptions = array();

        // Valid base attributes for SlugIt IDs from which slugs are created
        $slugitFromAttributes = Yii::app()->workflowUi->slugitFromAttributes;
        $slugitToAttributes = Yii::app()->workflowUi->slugitToAttributes;

        // Auto-generate slug from title
        if (in_array($originalAttribute, $slugitFromAttributes) && !isset($fieldOptions['disableSlug'])) {
            $htmlOptions['class'] = Html::ITEM_FORM_FIELD_CLASS . ' slugit-from-1';
        } else if (in_array($originalAttribute, $slugitToAttributes) && !isset($fieldOptions['disableSlug'])) {
            $htmlOptions['class'] = Html::ITEM_FORM_FIELD_CLASS . ' slugit-to-1';
        }

        // Bind slug field
        Html::jsSlugIt(array(
            '.slugit-from-1' => '.slugit-to-1',
        ));

        return $htmlOptions;

    }

    /**
     * Creates an item slug text field control group with support for SlugIt auto-generation.
     * @param ActiveRecord $model
     * @param string $attribute
     * @param array $htmlOptions
     * @return string
     */
    public function itemSlugTextFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        $titleClass = 'slugit-from-1';
        $slugClass = 'slugit-to-1';

        $classes = array();
        if (isset($htmlOptions['class'])) {
            $classes = explode(' ', $htmlOptions['class']);
        }
        $classes[] = $slugClass;
        $htmlOptions['class'] = implode(' ', $classes);

        Html::jsSlugIt(array(
            ".$titleClass" => ".$slugClass",
        ));

        return parent::textFieldControlGroup($model, $attribute, $htmlOptions);
    }

    /**
     * Renders a pre-rendered custom field input row.
     *
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param string $input the rendered input.
     * @param array $htmlOptions the HTML options.
     *
     * @return string the generated row
     */
    public function customControlGroup($model, $attribute, $input, $htmlOptions = array())
    {
        return TbHtml::customActiveControlGroup($input, $model, $attribute, $htmlOptions);
    }

    /**
     * Creates a control group.
     * @param string $type
     * @param ActiveRecord $model
     * @param string $attribute
     * @param array $htmlOptions
     * @param array $data
     * @return string
     */
    public function createControlGroup($type, $model, $attribute, $htmlOptions = array(), $data = array())
    {
        $htmlOptions[self::DATA_ORIGINAL_VALUE] = $this->getAttributeValue($model, $attribute);

        if ($model->asa('i18n-attribute-messages') !== null) {
            $model = $model->edited();
        }

        return parent::createControlGroup(
            $type,
            $model,
            $attribute,
            $htmlOptions,
            $data
        );
    }

    /**
     * Generates an input for a model attribute.
     * @param string $type
     * @param ActiveRecord $model
     * @param string $attribute
     * @param array $htmlOptions
     * @param array $data
     * @return string
     */
    public function createInput($type, $model, $attribute, $htmlOptions = array(), $data = array())
    {
        $htmlOptions[self::DATA_ORIGINAL_VALUE] = $this->getAttributeValue($model, $attribute);
        return parent::createInput($type, $model, $attribute, $htmlOptions, $data);
    }

    /**
     * Returns the attribute value (returns an empty string if null).
     * Aa string value is required for HTML data attributes to be rendered.
     * @param ActiveRecord $model
     * @param string $attribute
     * @return string
     */
    public function getAttributeValue($model, $attribute)
    {
        if ($model instanceof ActiveRecord && !empty($model->$attribute)) {
            return $model->$attribute;
        } else {
            return '';
        }
    }

    /**
     * Selects the current related models for a dropDownList
     * Use for htmlOptions options-key
     * @see http://www.yiiframework.com/doc/api/1.1/CHtml#activeDropDownList-detail
     * @param $model
     * @param $relation
     * @param string $idField
     * @return array
     */
    public function selectRelated($model, $relation, $idField = 'id')
    {
        $options = array();
        foreach ($model->{$relation} as $related) {
            $options[$related->{$idField}] = array('selected' => true);
        }
        return $options;
    }

}