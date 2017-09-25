<?php
namespace MeuUniverso\Controller;

use Application\Entity\Movie\Movie;
use Application\Entity\Registration\Registration;

class IndexController extends AbstractMeuUniversoController
{
    public function indexAction()
    {
        $movies = $this->getRepository(Movie::class)->findBy([
           'author' => $this->getAuthenticationService()->getIdentity()->getId()
        ],['createdAt' => 'DESC']);

        return [
            'movies' => $movies
        ];
    }

    public function erroAction()
    {
        $errorCode = $this->params()->fromQuery('code');

        $idReg = $this->params()->fromQuery('id_reg');
        $reg = null;
        if($idReg) {
            $reg = $this->getRepository(Registration::class)->findOneBy([
                'hash' => $idReg
            ]);
        }

        return [
            'code' => $errorCode,
            'reg' => $reg
        ];
    }

    public function sucessoAction()
    {
        return [];
    }
}
