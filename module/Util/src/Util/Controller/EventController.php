<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 02/02/2016
 * Time: 12:27
 */

namespace Util\Controller;

use Application\Entity\Event\Event;
use Util\Security\Crypt;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class EventController extends AbstractActionController
{
	public function eventAction()
	{
		$jsonModel = new JsonModel();
		$jsonModel->setTerminal(true);

        try {

            $eventId = $this->params()->fromPost('event');
            if (!$eventId) {
                $jsonModel->error = "Evento não informado!";
            }

            $event = $this->getRepository(Event::class)->find($eventId);

            $places[] = '<option>Selecione</option>';
            foreach($event->getPlaces() as $c) {
                $places[] = '<option value="'.$c->getId().'">'.$c->getName().'</option>';
            }
            $jsonModel->places = $places;

            $subEvents[] = '<option>Selecione</option>';
            foreach($event->getSubEvents() as $c) {
                $subEvents[] = '<option value="'.$c->getId().'">'.$c->getName().'</option>';
            }
            $jsonModel->subEvents = $subEvents;

        } catch(\Exception $e) {
            $jsonModel->error = 'Não foi possível executar a atualização. Por favor, tente novamente.';
        }

        return $jsonModel;
	}
}