<?php

class TbProgress extends CWidget
{
    /**
     * @var string the bar color. Valid values are 'info', 'success', and 'danger'.
     */
    public $color;

    /**
     * @var boolean indicates whether the bar is striped.
     */
    public $striped = false;

    /**
     * @var boolean indicates whether the bar is animated.
     */
    public $animated = false;

    /**
     * @var integer the amount of progress in percent.
     */
    public $percent = 0;

    /**
     * @var array the HTML attributes for the widget container.
     */
    public $htmlOptions = array();

    /**
     *
     */
    public function run()
    {
        foreach (array('striped', 'animated') as $attribute) {
            $this->htmlOptions[$attribute] = $this->$attribute;
        }
        $this->htmlOptions['color'] = $this->color;
        echo TbHtml::progressBar($this->percent, $this->htmlOptions);
    }
} 