<?php
namespace MeuUniverso\Controller;

use Application\Entity\Movie\Movie;
use Application\Entity\Registration\Registration;
use Application\Entity\Workshop\WorkshopSubscription;

class IndexController extends AbstractMeuUniversoController
{
    public function indexAction()
    {
        $movies = $this->getRepository(Movie::class)->findBy([
           'author' => $this->getAuthenticationService()->getIdentity()->getId()
        ],['createdAt' => 'DESC']);

        $idUserAndDependents = [$this->getAuthenticationService()->getIdentity()->getId()];
        foreach ($this->getAuthenticationService()->getIdentity()->getDependents() as $dep) {
            array_push($idUserAndDependents, $dep->getId());
        }

        $workshopQb = $this->getRepository(WorkshopSubscription::class)->createQueryBuilder('w');
        $workshops = $workshopQb
            ->andWhere($workshopQb->expr()->in('w.user', ':idUserAndDependents'))
            ->setParameter('idUserAndDependents', $idUserAndDependents)
            ->getQuery()
            ->getResult();
        return [
            'movies' => $movies,
            'workshops' => $workshops
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
