<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 18/09/2017
 * Time: 22:24
 */

namespace MeuUniverso\View\Helper;


use Application\Entity\Movie\MovieEventStatus;
use Application\Entity\Programing\Programing;
use Application\Entity\Registration\Options;
use Application\Entity\Registration\Status;
use Application\Entity\Seminar\SeminarSubscription;
use Application\Entity\SessionSchool\SessionSchoolSubscription;
use Application\Entity\Workshop\WorkshopSubscription;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class UserSeminar extends AbstractHelper
    implements ServiceLocatorAwareInterface
{
    private $serviceLocator;

    public function __invoke($sessions=[])
    {
        $authService = null;
        if($this->getServiceLocator()->getServiceLocator()->has('meuuniverso_authenticationservice')) {
            $authService = $this->getServiceLocator()->getServiceLocator()->get('meuuniverso_authenticationservice');
        } else {
            throw new \Exception('Authentication Service not definied');
        }

        return $this->render($sessions);
    }

    protected function render($subscriptions=[])
    {
        if(!count($subscriptions)) {
            return '<p>Nenhum registro localizado</p>';
        }

        $tableFormat = '<table class="table table-hover table-light table-movie">
                        <thead>
                            <tr>
                                <th width="20%%"> Evento </th>
                                <th width="20%%"> Seminário </th>
                                <th width="20%%"> Data de cadastro </th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>%s</tbody>
                    </table>';
        $rows = "";
        foreach ($subscriptions as $m) {
            $rows.= $this->row($m);
        }

        return sprintf($tableFormat, $rows);
    }

    protected function row(SeminarSubscription $item)
    {
        $td = '<td>'.$item->getEvent()->getShortName().'</td>';
        $td.= '<td>'.$item->getSeminarCategory()->getName().'</td>';
        $td.= '<td>'.$item->getCreatedAt()->format('d/mY \à\s H:i').'</td>';

        $urlHelper = $this->getServiceLocator()->get('url');

        $comproveUrl = $urlHelper('meu-universo/seminar', [
            'action' => 'comprovante',
            'id_reg' => $item->getRegistration()->getHash(),
            'id' => $item->getId()
        ]);
        $btnComprovante = '<a 
            href="'.$comproveUrl.'" 
            type="button" 
            class="btn btn-circle btn-default btn-sm">
                <i class="glyphicon glyphicon-list-alt"></i> Comprovante de participação</a>';


        $td.= "<td>$btnComprovante</td></tr>";
        return $td;
    }

    protected function labelStatus($status)
    {
        $format = '<span class="label label-sm %s">%s</span>';

        $color = "";
        switch($status) {
            case Status::ON_EVALUATION:
                $color = 'label-warning';
                break;
            case Status::NOT_SELECTED:
                $color = 'label-danger';
                break;
            case Status::SELECTED:
            case Status::CONFIRMED:
                $color = 'label-success';
                break;
        }

        return sprintf($format, $color, Status::get($status));
    }

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function getServiceLocator() {
        return $this->serviceLocator;
    }
}