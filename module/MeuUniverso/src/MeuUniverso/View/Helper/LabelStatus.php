<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 18/09/2017
 * Time: 22:24
 */

namespace MeuUniverso\View\Helper;


use Application\Entity\Movie\Movie;
use Application\Entity\Movie\MovieEventStatus;
use Application\Entity\Project\Project;
use Application\Entity\Registration\Options;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\DateTime;
use Zend\View\Helper\AbstractHelper;

class LabelStatus extends AbstractHelper
{

    public function __invoke($status)
    {
        $format = '<span class="label label-sm %s">%s</span>';

        $color = "";
        switch($status) {
            case MovieEventStatus::ON_EVALUATION:
                $color = 'label-warning';
                break;
            case MovieEventStatus::NOT_SELECTED:
                $color = 'label-danger';
                break;
            case MovieEventStatus::SELECTED:
                $color = 'label-success';
                break;
        }

        return sprintf($format, $color, MovieEventStatus::get($status));
    }
}