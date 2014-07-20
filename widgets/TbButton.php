<?php

class TbButton extends CWidget
{
    // Button callback types.
    const BUTTON_LINK = 'link';
    const BUTTON_BUTTON = 'button';
    const BUTTON_SUBMIT = 'submit';
    const BUTTON_SUBMITLINK = 'submitLink';
    const BUTTON_RESET = 'reset';
    const BUTTON_AJAXLINK = 'ajaxLink';
    const BUTTON_AJAXBUTTON = 'ajaxButton';
    const BUTTON_AJAXSUBMIT = 'ajaxSubmit';
    const BUTTON_INPUTBUTTON = 'inputButton';
    const BUTTON_INPUTSUBMIT = 'inputSubmit';

    // Button types.
    const TYPE_PRIMARY = 'primary';
    const TYPE_INFO = 'info';
    const TYPE_SUCCESS = 'success';
    const TYPE_WARNING = 'warning';
    const TYPE_DANGER = 'danger';
    const TYPE_INVERSE = 'inverse';
    const TYPE_LINK = 'link';

    // Button sizes.
    const SIZE_MINI = 'mini';
    const SIZE_SMALL = 'small';
    const SIZE_LARGE = 'large';

    /**
     * @var string the button callback types.
     * Valid values are 'link', 'button', 'submit', 'submitLink', 'reset', 'ajaxLink', 'ajaxButton' and 'ajaxSubmit'.
     */
    public $buttonType = TbHtml::BUTTON_TYPE_LINK;

    /**
     * @var string the button color.
     */
    public $color = TbHtml::BUTTON_COLOR_DEFAULT;

    /**
     * @var string the button size.
     */
    public $size;

    /**
     * @var string the button icon, e.g. 'ok' or 'remove white'.
     */
    public $icon;

    /**
     * @var string the button label.
     */
    public $label;

    /**
     * @var string the button URL.
     */
    public $url;

    /**
     * @var boolean indicates whether the button should span the full width of the a parent.
     */
    public $block = false;

    /**
     * @var boolean indicates whether the button is active.
     */
    public $active = false;

    /**
     * @var boolean indicates whether the button is disabled.
     */
    public $disabled = false;

    /**
     * @var boolean indicates whether to encode the label.
     */
    public $encodeLabel = true;

    /**
     * @var boolean indicates whether to enable toggle.
     */
    public $toggle;

    /**
     * @var string the loading text.
     */
    public $loadingText;

    /**
     * @var string the complete text.
     */
    public $completeText;

    /**
     * @var array the dropdown button items.
     */
    public $items;

    /**
     * @var array the HTML attributes for the widget container.
     */
    public $htmlOptions = array();

    /**
     * @var array the button ajax options (used by 'ajaxLink' and 'ajaxButton').
     */
    public $ajaxOptions = array();

    /**
     * @var array the HTML attributes for the dropdown menu.
     */
    public $dropdownOptions = array();

    /**
     * @var bool whether the button is visible or not
     */
    public $visible = true;

    /**
     *
     */
    public function run()
    {
        if (!$this->visible) {
            return;
        }
        foreach (array('color', 'size', 'icon', 'url', 'block', 'toggle', 'loadingText', 'completeText', 'items', 'ajaxOptions') as $attribute) {
            $this->htmlOptions[$attribute] = $this->$attribute;
        }
        $this->htmlOptions['menuOptions'] = $this->dropdownOptions;
        echo TbHtml::btn($this->buttonType, $this->label, $this->htmlOptions);
    }
} 