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
use Application\Entity\Registration\Options;
use Application\Entity\Workshop\WorkshopSubscription;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Stdlib\DateTime;
use Zend\View\Helper\AbstractHelper;

class UserWorkshops extends AbstractHelper
    implements ServiceLocatorAwareInterface
{
    private $serviceLocator;

    public function __invoke($workshops=[])
    {
        $authService = null;
        if($this->getServiceLocator()->getServiceLocator()->has('meuuniverso_authenticationservice')) {
            $authService = $this->getServiceLocator()->getServiceLocator()->get('meuuniverso_authenticationservice');
        } else {
            throw new \Exception('Authentication Service not definied');
        }

        return $this->render($workshops);
    }

    protected function render($workshops=[])
    {
        if(!count($workshops)) {
            return '<p>Nenhum registro localizado</p>';
        }

        $tableFormat = '<table class="table table-hover table-light table-movie">
                        <thead>
                            <tr>
                                <th> Inscrição para </th>
                                <th width="20%%"> Evento </th>
                                <th width="20%%"> Oficina </th>
                                <th width="20%%"> Data de cadastro </th>
                                <th width="30%%"> Status </th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>%s</tbody>
                    </table>';
        $rows = "";
        foreach ($workshops as $m) {
            $rows.= $this->row($m);
        }

        return sprintf($tableFormat, $rows);
    }

    protected function row(WorkshopSubscription $workshop)
    {
        $td = '<tr><td>'.$workshop->getUser()->getName().'</td>';
        $td.= '<td>'.$workshop->getEvent()->getShortName().'</td>';
        $td.= '<td>'.$workshop->getName().'</td>';
        $td.= '<td>'.$workshop->getCreatedAt()->format('d/mY \à\s H:i').'</td>';
        $td.= '<td>'.$this->labelStatus($workshop->getStatus()).'</td>';

        $urlHelper = $this->getServiceLocator()->get('url');

        //visualizar
        $viewUrl = '#';
        $btnView = '<a 
            href="'.$viewUrl.'" 
            type="button" 
            class="btn btn-circle btn-default btn-sm">
                <i class="glyphicon glyphicon-list-alt"></i> Confirmação de participação</a>';

        $td.= '<td>'.$btnView.'</td></tr>';
        return $td;
    }

    protected function labelStatus($status)
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

    public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }

    public function getServiceLocator() {
        return $this->serviceLocator;
    }
}