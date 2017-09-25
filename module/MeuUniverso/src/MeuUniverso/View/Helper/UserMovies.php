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
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class UserMovies extends AbstractHelper implements ServiceLocatorAwareInterface
{
    private $serviceLocator;

    public function __invoke($movies=[])
    {
        $authService = null;
        if($this->getServiceLocator()->getServiceLocator()->has('meuuniverso_authenticationservice')) {
            $authService = $this->getServiceLocator()->getServiceLocator()->get('meuuniverso_authenticationservice');
        } else {
            throw new \Exception('Authentication Service not definied');
        }

        return $this->render($movies);
    }

    protected function render($movies=[])
    {
        if(!count($movies)) {
            return '<p>Nenhum filme cadastrado</p>';
        }

        $tableFormat = '<table class="table table-hover table-light table-movie">
                        <thead>
                            <tr>
                                <th> # </th>
                                <th> Título </th>
                                <th> Data de cadastro </th>
                                <th width="27%%"> Status </th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>%s</tbody>
                    </table>';
        $rows = "";
        foreach ($movies as $m) {
            $rows.= $this->row($m);
        }

        return sprintf($tableFormat, $rows);
    }

    protected function row(Movie $movie)
    {
        $td = '<tr><td>'.$movie->getId().'</td>';
        $td.= '<td>'.$movie->getTitle().'</td>';
        $td.= '<td>'.$movie->getCreatedAt()->format('d/mY \à\s H:i').'</td>';

        $events = "";
        foreach ($movie->getEvents() as $event) {
            $events.= '<span class="table-movie-event">
                <label>'.$event->getEvent()->getShortName().'</label>'.$this->labelStatus($event->getStatus()). '</span>';
        }
        $td.= "<td>$events</td>";

        $now = new \DateTime();
        $reg = $movie->getRegistration();
        if($now >= $reg->getEditRegisterUntil()) {
            $btnEditar = '<a href="javascript:;" class="btn btn-circle default disabled"> Editar </a>';
        } else {
            $urlHelper = $this->getServiceLocator()->get('url');

            $editUrl = $urlHelper('meu-universo/movie', ['id_reg'=>$reg->getHash(), 'id'=>$movie->getId(), 'action'=>'editar']);
            $btnEditar = '<a href="'.$editUrl.'" type="button" class="btn btn-circle btn-default btn-sm"><i class="glyphicon glyphicon-edit"></i> Editar</a>';
        }
        $td.= '<td>'.$btnEditar.'</td></tr>';
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