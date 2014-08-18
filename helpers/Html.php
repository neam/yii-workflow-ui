<?php

class Html extends TbHtml
{
    // HTML class constants.
    const ITEM_FORM_FIELD_CLASS = 'span9';

    /**
     * Registers the head tags.
     */
    static public function registerHeadTags()
    {
        $clientScript = Yii::app()->getClientScript();
        $clientScript->registerMetaTag('width=device-width, initial-scale=1.0', 'viewport');
        $clientScript->registerLinkTag('shortcut icon', null, '/favicon.ico', null, null);
    }

    /**
     * Renders a widget with the given properties.
     * @param string $className widget class name.
     * @param array $properties widget properties.
     * @return string rendered widget.
     */
    protected static function renderWidget($className, $properties)
    {
        return Yii::app()->controller->widget($className, $properties, true);
    }

    /**
     * Removes values from the given options that should not be passed to widgets.
     * @param array $htmlOptions a list of options.
     */
    protected static function createWidgetOptions($htmlOptions)
    {
        $widgetOptions = $htmlOptions;
        TbArray::removeValues(
            array(
                'groupOptions',
                'controlOptions',
                'label',
                'labelOptions',
                'error',
                'errorOptions',
                'help',
                'helpOptions',
                'widgetOptions'
            ),
            $widgetOptions
        );
        return $widgetOptions;
    }

    /**
     * @param $model
     * @param $attribute
     * @param array $data
     * @param array $htmlOptions
     * @return string
     */
    static public function activeSelect2($model, $attribute, $data = array(), $htmlOptions = array())
    {
        $options = TbArray::merge(
            TbArray::popValue('pluginOptions', $htmlOptions, array()),
            array(
                'minimumResultsForSearch' => 20,
            )
        );
        $widgetOptions = self::createWidgetOptions($htmlOptions);
        $properties = TbArray::merge(
            TbArray::popValue('widgetOptions', $htmlOptions, array()),
            array(
                'model' => $model,
                'attribute' => $attribute,
                'data' => $data,
                'pluginOptions' => $options,
                'htmlOptions' => $widgetOptions,
            )
        );

        $html = self::renderWidget('vendor.crisu83.yiistrap-widgets.widgets.TbSelect2', $properties);

        if (isset($htmlOptions['thumbnails']) && $htmlOptions['thumbnails']) {
            self::renderSelect2Thumbnails($model, $attribute);
        }

        return $html;
    }

    /**
     * @param $model
     * @param $attribute
     * @param array $data
     * @param array $htmlOptions
     * @return string
     */
    static public function activeSelect2ControlGroup($model, $attribute, $data = array(), $htmlOptions = array())
    {
        $input = self::activeSelect2($model, $attribute, $data, $htmlOptions);
        return TbHtml::customActiveControlGroup($input, $model, $attribute, $htmlOptions);
    }

    /**
     * Renders thumbnails for Select2 options.
     * @param CActiveRecord $model
     * @param string $attribute
     */
    static public function renderSelect2Thumbnails($model, $attribute)
    {
        // TODO: Get rid of this atrocious jQuery hack.

        $baseUrl = Yii::app()->baseUrl;
        $modelClass = get_class($model);

        $js = <<<EOF
(function() {
    function format(state) {
        if (!state.id) return state.text;

        var html = '';

        html += '<div class="row">';
        html += '  <div class="col-xs-6">';
        html += "    <div class='select2-text'>" + state.text + "</div>";
        html += '  </div>';
        html += '  <div class="col-xs-6" style="text-align: right;">';
        html += "    <img class='select2-thumb' src='{$baseUrl}/p3media/file/image?preset=select2-thumb&id=" + state.id.toLowerCase() + "'>";
        html += '  </div>';
        html += '</div>';

        return html;
    }

    var select2opts = {
        formatResult: format,
        formatSelection: format,
        //escapeMarkup: function(m) { return m; }
    };

    $("#{$modelClass}_{$attribute}").data('select2opts', select2opts);
    $("#{$modelClass}_{$attribute}").select2($("#{$modelClass}_{$attribute}").data('select2opts'));
})();
EOF;

        Yii::app()->clientScript->registerScript('select2-with-thumbnails-' . $modelClass . '-' . $attribute, $js);
    }

    /**
     * Renders the backend navbar.
     */
    static public function renderBackendNavbar()
    {
        $role = 'Editor'; // required role for rendering backend navbar
        if (Yii::app()->user->checkAccess($role)) {
            app()->controller->renderPartial('application.themes.backend2.views.layouts._navbar');
        }
    }

    /**
     * Registers the Dirty Forms jQuery plugin and binds it to a form element.
     */
    static public function jsDirtyForms()
    {
        self::jsFacebox(); // required by Dirty Forms
        publishJs(Yii::getPathOfAlias('vendor.neam.yii-workflow-ui.themes.simplicity') . '/js/vendor/jquery.dirtyforms.js', CClientScript::POS_HEAD);
        publishJs(Yii::getPathOfAlias('vendor.neam.yii-workflow-ui.themes.simplicity') . '/js/dirty-forms-ckeditor.js', CClientScript::POS_HEAD);

        $title = Yii::t('app', 'You have unsaved changes');
        $message = Yii::t('app', 'Would you like to continue without saving?');

        $js = "
        $.DirtyForms.title = '{$title}';
        $.DirtyForms.message = '{$message}';
        $('form.dirtyforms').dirtyForms();
        ";

        app()->clientScript->registerScript('registerDirtyForms', $js, CClientScript::POS_END);
        publishJs(Yii::getPathOfAlias('vendor.neam.yii-workflow-ui.themes.simplicity') . '/js/toggle-dirty-buttons.js', CClientScript::POS_READY); // show action buttons when form is dirty
    }

    /**
     * Registers the Facebox jQuery plugin.
     */
    static public function jsFacebox()
    {
        publishJs(Yii::getPathOfAlias('vendor.neam.yii-workflow-ui.themes.simplicity') . '/js/vendor/facebox/facebox.js', CClientScript::POS_HEAD);
        publishCss(Yii::getPathOfAlias('vendor.neam.yii-workflow-ui.themes.simplicity') . '/js/vendor/facebox/assets/facebox.css');
        $closeImage = app()->baseUrl . '/images/facebox/closelabel.png';
        $loadingImage = app()->baseUrl . '/images/facebox/loading.gif';
        app()->clientScript->registerScript('registerFacebox', "$.facebox.settings.closeImage = '{$closeImage}'; $.facebox.settings.loadingImage = '{$loadingImage}'", CClientScript::POS_END);
    }

    /**
     * Registers the SlugIt jQuery plugin.
     * @param array $fields the from and to IDs (e.g. array('#from' => '#to').
     * @param string $separator the separator. Defaults to '-'.
     */
    static public function jsSlugIt($fields = array(), $separator = '-')
    {
        publishJs(Yii::getPathOfAlias('vendor.neam.yii-workflow-ui.themes.simplicity') . '/js/slugit.js', CClientScript::POS_HEAD);
        foreach ($fields as $from => $to) {
            app()->clientScript->registerScript('slugIt_' . $from, "jQuery('$from').slugIt({output: '$to', separator: '$separator'});", CClientScript::POS_END);
            app()->clientScript->registerScript('slugItOnLoad_' . $to, "if (jQuery('$to').length > 0 && jQuery('$to').val().length === 0) jQuery('$from').trigger(jQuery.Event('keypress', {which: 27 /* ESC key */}));", CClientScript::POS_READY);
        }
    }

    /**
   	 * Generates a tooltip. (This overrides the currently faulty TbHtml::tooltip() method.)
   	 * @param string $label the tooltip link label text.
   	 * @param mixed $url the link url.
   	 * @param string $content the tooltip content text.
   	 * @param array $htmlOptions additional HTML attributes.
   	 * @return string the generated tooltip.
   	 */
    static public function tooltip($label, $url, $content, $htmlOptions = array())
    {
        $htmlOptions['data-toggle'] = 'tooltip'; // this option is missing from TbHtml::tooltip()
        return parent::tooltip($label, $url, $content, $htmlOptions);
    }

    /**
     * Creates a custom label for an CActiveRecord field with an attribute hint tooltip.
     * @param CActiveRecord $model the model.
     * @param string $attribute the model attribute.
     * @param string $hintAttribute the attribute hint (if different). Defaults to $attribute.
     * @return string the label HTMl.
     */
    static public function attributeLabelWithTooltip(CActiveRecord $model, $attribute, $hintAttribute = '', $htmlOptions = array())
    {
        $hintAttribute = !empty($hintAttribute) ? $hintAttribute : $attribute;
        $label = $model->getAttributeLabel($attribute);
        $tooltip = $model->getAttributeHint($hintAttribute)
            ? ' ' . Html::hintTooltip($model->getAttributeHint($hintAttribute), $htmlOptions)
            : '';
        return $label . $tooltip;
    }

    /**
     * Creates a tooltip for a model attribute hint.
     * @param string $content the tooltip content.
     * @return string the tooltip HTML.
     */
    static public function hintTooltip($content, $htmlOptions = array())
    {
        $htmlOptions['class'] = 'hint-tooltip';
        return isset($content)
            ? Html::tooltip(TbHtml::icon(TbHtml::ICON_QUESTION_SIGN), '#', $content, $htmlOptions)
            : '';
    }

    /**
     * Creates a link button with an icon.
     * @param string $icon the icon class.
     * @param string $label the button label.
     * @param array $htmlOptions
     * @return string
     */
    static public function linkButtonWithIcon($icon, $label, $htmlOptions = array())
    {
        TbHtml::addCssClass('btn-icon-link', $htmlOptions);
        $htmlOptions['icon'] = $icon;
        $label = '<span class="btn-label">' . $label . '</span>';
        return self::linkButton($label, $htmlOptions);
    }

    /**
     * Creates a static text element that adopts the dimensions of a text field.
     * @param string $value the field value.
     * @return string
     */
    static public function staticTextField($value)
    {
        $tag = 'span';
        $html = self::openTag($tag, array(
            'class' => 'static-text-field',
        ));
        $html .= $value;
        $html .= self::closeTag($tag);
        return $html;
    }

    /**
     * Creates a static text element that adopts the dimensions of a text field control group.
     * @param string $label the field label.
     * @param string $value the field value.
     * @return string
     */
    static public function staticTextFieldControlGroup($label, $value)
    {
        $html = self::openTag('span', array(
            'class' => 'static-text-field-label',
        ));
        $html .= $label;
        $html .= self::closeTag('span');
        $html .= self::staticTextField($value);
        return $html;
    }

    /**
     * Creates a static text element that adopts the dimensions of an active text field.
     * @param CActiveRecord $model
     * @param string $attribute
     * @return string
     */
    static public function activeStaticTextField($model, $attribute)
    {
        $tag = 'span';
        $html = self::openTag($tag, array(
            'class' => 'static-text-field',
        ));
        $html .= $model->{$attribute};
        $html .= self::closeTag($tag);
        return $html;
    }

    /**
     * Creates a static text element that adopts the dimensions of an active text field control group.
     * @param CActiveRecord $model
     * @param string $attribute
     * @return string
     */
    static public function activeStaticTextFieldControlGroup($model, $attribute, $htmlOptions = array())
    {
        if (isset($htmlOptions['label'])) {
            $label = $htmlOptions['label'];
        } else {
            $label = $model->getAttributeLabel($attribute);
        }

        $html = self::openTag('span', array(
            'class' => 'static-text-field-label',
        ));
        $html .= $label;
        $html .= self::closeTag('span');
        $html .= self::activeStaticTextField($model, $attribute);
        return $html;
    }

    /**
     * Returns a list of available languages (language codes, e.g. 'en_us').
     * @return array
     */
    static public function getLanguages()
    {
        $languages = array(
            'en'    => Yii::t('language', 'English'),
            'es'    => Yii::t('language', 'Spanish'),
            'hi'    => Yii::t('language', 'Hindi'),
            'pt'    => Yii::t('language', 'Portuguese'),
            'sv'    => Yii::t('language', 'Swedish'),
            'de'    => Yii::t('language', 'German'),
            'zh'    => Yii::t('language', 'Chinese'),
            'ar'    => Yii::t('language', 'Arabic'),
            'bg'    => Yii::t('language', 'Bulgarian'),
            'ca'    => Yii::t('language', 'Catalan'),
            'cs'    => Yii::t('language', 'Czech'),
            'da'    => Yii::t('language', 'Danish'),
            'en_gb' => Yii::t('language', 'English (United Kingdom)'),
            'en_us' => Yii::t('language', 'English (United States)'),
            'el'    => Yii::t('language', 'Greek'),
            'fi'    => Yii::t('language', 'Finnish'),
            'fil'   => Yii::t('language', 'Filipino'),
            'fr'    => Yii::t('language', 'French'),
            'hr'    => Yii::t('language', 'Croatian'),
            'hu'    => Yii::t('language', 'Hungarian'),
            'id'    => Yii::t('language', 'Indonesian'),
            'iw'    => Yii::t('language', 'Hebrew'),
            'it'    => Yii::t('language', 'Italian'),
            'ja'    => Yii::t('language', 'Japanese'),
            'ko'    => Yii::t('language', 'Korean'),
            'lt'    => Yii::t('language', 'Lithuanian'),
            'lv'    => Yii::t('language', 'Latvian'),
            'nl'    => Yii::t('language', 'Dutch'),
            'no'    => Yii::t('language', 'Norwegian'),
            'pl'    => Yii::t('language', 'Polish'),
            'pt_br' => Yii::t('language', 'Portuguese (Brazil)'),
            'pt_pt' => Yii::t('language', 'Portuguese (Portugal)'),
            'ro'    => Yii::t('language', 'Romanian'),
            'ru'    => Yii::t('language', 'Russian'),
            'sk'    => Yii::t('language', 'Slovak'),
            'sl'    => Yii::t('language', 'Slovenian'),
            'sr'    => Yii::t('language', 'Serbian'),
            'th'    => Yii::t('language', 'Thai'),
            'tr'    => Yii::t('language', 'Turkish'),
            'uk'    => Yii::t('language', 'Ukranian'),
            'vi'    => Yii::t('language', 'Vietnamese'),
            'zh_cn' => Yii::t('language', 'Chinese (PRC)'),
            'zh_tw' => Yii::t('language', 'Chinese (Taiwan & Hong Kong)'),
        );

        asort($languages); // sort alphabetically

        return $languages;
    }

    /**
     * Renders the Gapminder logo.
     * @param string $alt the image alt text.
     * @param array $htmlOptions
     * @return string
     */
    static public function renderLogo($alt = '', $htmlOptions = array())
    {
        return TbHtml::image(
            Yii::app()->baseUrl . '/images/logo.png',
            $alt,
            $htmlOptions
        );
    }

    /**
     * Renders the Gapminder logo with a link to the home page.
     * @param string $alt the image alt text.
     * @param array $imageHtmlOptions
     * @param array $linkHtmlOptions
     * @return string
     */
    static public function renderLogoWithLink($alt = '', $imageHtmlOptions = array(), $linkHtmlOptions = array())
    {
        return TbHtml::link(
            self::renderLogo($alt, $imageHtmlOptions),
            Yii::app()->homeUrl,
            $linkHtmlOptions
        );
    }

    /**
     * Wrapper of CHtml::activeId with support for fallback attribute
     * @param CModel $model the data model
     * @param string $attribute the attribute
     * @param string $fallbackAttribute (optional) the attribute to fall back on if $attribute is not set. Defaults to id
     * @return string result of CHtml::activeId()
     */
    static public function generateActiveId($model, $attribute, $fallbackAttribute = 'id')
    {
        if (!isset($model->{$attribute})) {
            $attribute = $fallbackAttribute;
        }
        return CHtml::activeId($model, $model->{$attribute});
    }

    /**
     * Renders an array of errors as an unordered list.
     * @param array $errors list of errors (as attribute => message)
     * @param string $intro the introductory error message. Defaults to 'Please correct the following errors:'.
     * @return string
     */
    static public function renderValidationErrors(array $errors, $intro = null)
    {
        $html = '';
        $errorCount = count($errors);

        if ($errorCount > 0) {
            if (!isset($intro)) {
                $intro = Yii::t('app', 'Please correct the following error:|Please correct the following errors:', array($errorCount));
            }

            $html .= "<p>$intro</p>";
            $html .= '<ul>';
            foreach ($errors as $error) {
                $html .= "<li>$error</li>";

            }
            $html .= '</ul>';
        }

        return $html;
    }

}
