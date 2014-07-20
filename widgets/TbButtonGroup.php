<?php

class TbButtonGroup extends CWidget
{
    // Toggle options.
    const TOGGLE_CHECKBOX = 'checkbox';
    const TOGGLE_RADIO = 'radio';

    /**
     * @var string the button callback type.
     * @see BootButton::buttonType
     */
    public $buttonType = TbButton::BUTTON_LINK;

    /**
     * @var string the button type.
     * @see BootButton::type
     */
    public $type;

    /**
     * @var string the button size.
     * @see BootButton::size
     */
    public $size;

    /**
     * @var boolean indicates whether to encode the button labels.
     */
    public $encodeLabel = true;

    /**
     * @var array the HTML attributes for the widget container.
     */
    public $htmlOptions = array();

    /**
     * @var array the button configuration.
     */
    public $buttons = array();

    /**
     * @var boolean indicates whether to enable button toggling.
     */
    public $toggle;

    /**
     * @var boolean indicates whether the button group appears vertically stacked. Defaults to 'false'.
     */
    public $stacked = false;

    /**
     * @var boolean indicates whether dropdowns should be dropups instead. Defaults to 'false'.
     */
    public $dropup = false;
    /**
     * @var boolean indicates whether button is disabled or not. Defaults to 'false'.
     */
    public $disabled = false;

    /**
     *
     */
    public function run()
    {
        foreach (array('size', 'encodeLabel', 'toggle', 'stacked', 'dropup') as $attribute) {
            $this->htmlOptions[$attribute] = $this->$attribute;
        }
        $this->htmlOptions['color'] = $this->type;
        echo TbHtml::buttonGroup($this->buttons, $this->htmlOptions);
    }
}