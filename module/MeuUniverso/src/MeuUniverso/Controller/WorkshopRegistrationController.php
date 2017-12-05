<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 19/09/2017
 * Time: 11:17
 */

namespace MeuUniverso\Controller;


use Admin\Validator\Movie\Duration;
use Admin\Validator\Movie\Unique;
use Application\Entity\Event\Event;
use Application\Entity\Event\EventType;
use Application\Entity\Movie\Media;
use Application\Entity\Movie\Movie;
use Application\Entity\Movie\MovieSubscription;
use Application\Entity\Movie\Options as MovieOptions;
use Application\Entity\Registration\Options;
use Application\Entity\Registration\Registration;
use Doctrine\Common\Collections\ArrayCollection;
use DoctrineModule\Validator\UniqueObject;
use MeuUniverso\Form\MovieForm;
use Zend\View\Model\ViewModel;
use Zend\View\View;

class WorkshopRegistrationController extends AbstractMeuUniversoRegisterController
{
    const ERROR_REG_NOT_FOUND = 'x1001';
    const ERROR_REG_IS_CLOSED = 'x1002';
    const ERROR_REG_IS_NOT_EDIT = 'x1003';
    const ERROR_MOVIE_NOT_FOUNT = 'x1004';

    public function indexAction()
    {
        return [];
    }

    public function visualizarAction()
    {

    }

    protected function persist($method)
    {
        $idReg = $this->params()->fromRoute('id_reg');
        if(!$idReg) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }

        $reg = $this->getRepository(Registration::class)->findOneBy([
            'hash' => $idReg
        ]);

        if(!$reg) {
            return $this->redirect()->toRoute('meu-universo/default', [], ['query'=>[
                'code' => self::ERROR_REG_NOT_FOUND,
                'id_reg' => $idReg
            ]]);
        }


    }

    public function deleteAction()
    {
        return [];
    }
}