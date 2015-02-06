<?php

class AppActiveForm extends TbActiveForm
{
    const CONTROLLER_ACTION_TRANSLATE = 'translate';
    const DATA_ORIGINAL_VALUE = 'data-original-value';

    use WorkflowUiTbActiveFormTrait;
}
