<?php
namespace TwbBundle\Form\View\Helper;

use Zend\Form\View\Helper\FormElementErrors;

class TwbBundleFormElementErrors extends FormElementErrors
{
    protected $attributes = array(
        'class' => 'help-block'
    );

    protected $messageCloseString     = '</span>';

    protected $messageOpenFormat      = '<span%s>';

    protected $messageSeparatorString = '';
}
