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
        Status::SELECTED => '<span title="%s" class="label label-success"><i class="fa fa-check"></i></span>',
        Status::CONFIRMED => '<span title="%s" class="label label-success"><i class="fa fa-check"></i></span>',
        Status::NOT_SELECTED => '<span title="%s" class="label label-danger"><i class="fa fa-close"></i></span>',
        Status::ON_EVALUATION => '<span title="%s" class="label label-default"><i class="fa fa-cog"></i></span>',
        Status::STANDBY => '<span title="%s" class="label label-warning"><i class="fa fa-hourglass-half"></i></span>',
    ];


    public function __invoke($status, $label="")
    {
        return sprintf(
            $this->labelTemplate[$status],
            $label
        );
    }
}