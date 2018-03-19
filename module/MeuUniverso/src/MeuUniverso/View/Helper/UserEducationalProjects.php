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

class UserEducationalProjects extends AbstractHelper
    implements ServiceLocatorAwareInterface
{
    private $serviceLocator;

    public function __invoke($projects=[])
    {
        $authService = null;
        if($this->getServiceLocator()->getServiceLocator()->has('meuuniverso_authenticationservice')) {
            $authService = $this->getServiceLocator()->getServiceLocator()->get('meuuniverso_authenticationservice');
        } else {
            throw new \Exception('Authentication Service not definied');
        }

        return $this->render($projects);
    }

    protected function render($projects=[])
    {
        if(!count($projects)) {
            return '<p>Nenhum projeto cadastrado</p>';
        }

        $tableFormat = '<table class="table table-hover table-light table-movie">
                        <thead>
                            <tr>
                                <th> ID </th>
                                <th> Título </th>
                                <th width="30%%"> Status </th>
                                <th width="20%%"> Data de cadastro </th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>%s</tbody>
                    </table>';
        $rows = "";
        foreach ($projects as $m) {
            $rows.= $this->row($m);
        }

        return sprintf($tableFormat, $rows);
    }

    protected function row($project)
    {
        $td = '<tr><td>'.$project->getId().'</td>';
        $td.= '<td>'.$project->getTitle().'</td>';
        $td.= "<td>".$this->labelStatus($project->getStatus())."</td>";
        $td.= '<td>'.$project->getCreatedAt()->format('d/mY \à\s H:i').'</td>';

        $urlHelper = $this->getServiceLocator()->get('url');

        //visualizar
        //$viewUrl = $urlHelper('meu-universo/movie_view', ['id'=>$movie->getId()]);
        //$btnView = '<a href="'.$viewUrl.'" type="button" class="btn btn-circle btn-default btn-sm"><i class="glyphicon glyphicon-list-alt"></i> Visualizar</a>';

        $td.= '<td></td></tr>';
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