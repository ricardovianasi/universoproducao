<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 11/10/2017
 * Time: 15:27
 */

namespace Admin\View\Helper;

use Application\Entity\Registration\Status;
use Zend\View\Helper\AbstractHelper;

class RegistrationStatus extends AbstractHelper
{
    protected $labelTemplate = [
        Status::SELECTED => '<span data-original-title="%s" title="%s" class="label label-success tooltips"><i class="fa fa-check"></i></span>',
        Status::CONFIRMED => '<span data-original-title="%s" title="%s" class="label label-success tooltips"><i class="fa fa-check"></i></span>',
        Status::NOT_CONFIRMED => '<span data-original-title="%s" title="%s" class="label label-danger tooltips"><i class="fa fa-check"></i></span>',
        Status::NOT_SELECTED => '<span data-original-title="%s" title="%s" class="label label-danger tooltips"><i class="fa fa-close"></i></span>',
        Status::ON_EVALUATION => '<span data-original-title="%s" title="%s" class="label label-default tooltips"><i class="fa fa-cog"></i></span>',
        Status::STANDBY => '<span data-original-title="%s" title="%s" class="label label-warning tooltips"><i class="fa fa-hourglass-half"></i></span>',
    ];


    public function __invoke($status, $label="")
    {
        return sprintf(
            $this->labelTemplate[$status],
            $label,
            $label
        );
    }
}