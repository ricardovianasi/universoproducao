<?php
namespace MostratiradentesSp\Controller;

use Admin\Form\Movie\MovieForm;
use Admin\Form\Movie\MovieProgramingForm;
use Application\Controller\SiteController;
use Application\Entity\Movie\Movie;
use Application\Entity\Movie\OptionsType;
use Application\Entity\Programing\Programing;
use Application\Entity\Registration\Status;
use Application\Entity\Site\Site;
use Doctrine\ORM\Query\Expr\Join;
use Zend\View\Model\ViewModel;

class MovieController extends SiteController
{
    const SITE_ID = 15;

    public function indexAction()
    {
        $post = $this->params('post');
        $site = $this->getRepository(Site::class)->find(self::SITE_ID);
        $formFilter = new MovieProgramingForm($this->getEntityManager(), $site->getEvent());

        $qb = $this
            ->getRepository(Movie::class)
            ->createQueryBuilder('m')
            ->leftJoin(Programing::class, 'p', Join::WITH, 'm.id = p.objectId')
            ->innerJoin('m.subscriptions', 's')
            ->andWhere('s.event = :idEvent')
            ->andWhere('s.status = :status')
            ->orderBy('m.title', 'ASC')
            ->setParameters([
                'idEvent' => $site->getEvent()->getId(),
                'status' => Status::SELECTED
            ]);

        $data = $this->getRequest()->getQuery()->toArray();
        if(!empty($data['sub_event'])) {
            $qb->andWhere('p.subEvent = :idSubEvent')
                ->setParameter('idSubEvent', $data['sub_event']);
        }

        if(!empty($data['place'])) {
            $qb->andWhere('p.place = :place')
                ->setParameter('place', $data['place']);
        }

        if(!empty($data['category'])) {
            if($data['category'] == Movie::CATEGORY_LONGA) {
                $time = new \DateTime('01:00:00');
                $qb->andWhere('m.duration >= :time')
                    ->setParameter('time', $time);

            } elseif ($data['category'] == Movie::CATEGORY_MEDIA) {
                $timeIni = new \DateTime('00:30:00');
                $timeEnd = new \DateTime('01:00:00');
                $qb->andWhere('m.duration > :timeIni AND m.duration < :timeEnd')
                    ->setParameter('timeIni', $timeIni)
                    ->setParameter('timeEnd', $timeEnd);
            } elseif ($data['category'] == Movie::CATEGORY_CURTA) {
                $time = new \DateTime('00:30:00');
                $qb->andWhere('m.duration <= :time')
                    ->setParameter('time', $time);
            }
        }

        $movies = $qb->getQuery()->getResult();

        return new ViewModel([
            'movies' => $movies,
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs(),
            'form' => $formFilter->setData($data),
            'event' => $site->getEvent()
        ]);
    }

    public function movieAction()
    {
        $site = $this->getRepository(Site::class)->find(self::SITE_ID);

        $id = $this->params()->fromRoute('id');
        if(!$id) {
        }

        $qb = $this
            ->getRepository(Movie::class)
            ->createQueryBuilder('m')
            ->innerJoin('m.subscriptions', 's')
            ->andWhere('s.event = :idEvent')
            ->andWhere('s.status = :status')
            ->andWhere('m.id = :id')
            ->setParameters([
                'idEvent' => $site->getEvent()->getId(),
                'status' => Status::SELECTED,
                'id' => $id
            ]);

        /** @var Movie $movie */
        $movie = $qb->getQuery()->getOneOrNullResult();
        if(!$movie) {
            //não achou a notícia
        }



        $movieMedias = [];
        if($movie->getMovieDivulgation()) {
            $movieMedias[] = [
                'type' => 'video',
                'src' => $movie->getMovieDivulgation()
            ];
        }
        foreach ($movie->getMedias() as $media) {
            $movieMedias[] = [
                'type' => 'image',
                'src' => $media->getSrc(),
                'credits' => $media->getCredits()
            ];
        }

        $genre = $movie->getOption(OptionsType::GENRE);
        $color = $movie->getOption(OptionsType::COLOR);
        $format = $movie->getOption(OptionsType::FORMAT_COMPLETED);
        $duration = ($movie->getDuration()->format('H')*60)+($movie->getDuration()->format('i'));

        $breadcrumbs = [
            ['programacao' => 'Programação'],
            ['filmes' => 'Filmes']
        ];

        $movieDetails = [];

        if($genre) {
            $movieDetails[] = $genre->getName();
        }

        if($color) {
            $movieDetails[] = $color->getName();
        }

        if($format) {
            $movieDetails[] = $format->getName();
        }

        $movieDetails[] = $duration.' min';

        if($movie) {
            $movieDetails[] = $movie->getEndDateYear();
        }

        return new ViewModel([
            'post' => $movie,
            'medias' => $movieMedias,
            'breadcrumbs' => $breadcrumbs,
            'event' => $site->getEvent(),
            'movie_details' => $movieDetails
        ]);
    }
}
