<?php
namespace MeuUniverso\Controller;

use Application\Entity\EducationalProject\EducationalProject;
use Application\Entity\Movie\Movie;
use Application\Entity\Project\Project;
use Application\Entity\Proposal\ArtisticProposal;
use Application\Entity\Proposal\WorkshopProposal;
use Application\Entity\Registration\Registration;
use Application\Entity\Seminar\SeminarSubscription;
use Application\Entity\SessionSchool\SessionSchoolSubscription;
use Application\Entity\Workshop\WorkshopSubscription;

class IndexController extends AbstractMeuUniversoController
{
    public function indexAction()
    {
        $movies = $this->getRepository(Movie::class)->findBy([
           'author' => $this->getAuthenticationService()->getIdentity()->getId(),
           'type' => Movie::TYPE_MOVIE
        ],['createdAt' => 'DESC']);

        $moviesMotionCity = $this->getRepository(Movie::class)->findBy([
            'author' => $this->getAuthenticationService()->getIdentity()->getId(),
            'type' => Movie::TYPE_MOTION_CITY_MOVIE
        ],['createdAt' => 'DESC']);

        $educationalMovies = $this->getRepository(Movie::class)->findBy([
            'author' => $this->getAuthenticationService()->getIdentity()->getId(),
            'type' => Movie::TYPE_EDUCATIONAL_MOVIE
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

        $projects = $this->getRepository(Project::class)->findBy([
            'user' => $this->getAuthenticationService()->getIdentity()->getId()
        ],['createdAt' => 'DESC']);

        $educationalProjects = $this->getRepository(EducationalProject::class)->findBy([
            'user' => $this->getAuthenticationService()->getIdentity()->getId()
        ],['createdAt' => 'DESC']);

        $sessionSchool = $this->getRepository(SessionSchoolSubscription::class)->findBy([
            'user' => $this->getAuthenticationService()->getIdentity()->getId()
        ], ['createdAt' => 'DESC']);

        $seminarQb = $this->getRepository(SeminarSubscription::class)->createQueryBuilder('w');
        $seminars = $seminarQb
            ->andWhere($seminarQb->expr()->in('w.user', ':idUserAndDependents'))
            ->setParameter('idUserAndDependents', $idUserAndDependents)
            ->getQuery()
            ->getResult();

        $artisticProposal = $this->getRepository(ArtisticProposal::class)->findBy([
            'author' => $this->getAuthenticationService()->getIdentity()->getId()
        ], ['createdAt' => 'DESC']);

        $workshopProposal = $this->getRepository(WorkshopProposal::class)->findBy([
            'author' => $this->getAuthenticationService()->getIdentity()->getId()
        ], ['createdAt' => 'DESC']);


        return [
            'movies' => $movies,
            'motion_movies' => $moviesMotionCity,
            'educational_movies' => $educationalMovies,
            'workshops' => $workshops,
            'projects' => $projects,
            'educationalProjects' => $educationalProjects,
            'sessionSchool' => $sessionSchool,
            'seminars' => $seminars,
            'artisticProposal' => $artisticProposal,
            'workshopProposal' => $workshopProposal

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
