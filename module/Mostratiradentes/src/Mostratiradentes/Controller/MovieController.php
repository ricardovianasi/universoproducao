<?php
namespace Mostratiradentes\Controller;

use Admin\Form\Movie\MovieForm;
use Admin\Form\Movie\MovieProgramingForm;
use Application\Controller\SiteController;
use Application\Entity\Movie\Movie;
use Application\Entity\Movie\OptionsType;
use Application\Entity\Registration\Status;
use Application\Entity\Site\Site;
use Zend\View\Model\ViewModel;

class MovieController extends SiteController
{
    const SITE_ID = 10;

    public function indexAction()
    {
        $post = $this->params('post');
        $site = $this->getRepository(Site::class)->find(self::SITE_ID);
        $formFilter = new MovieProgramingForm($this->getEntityManager(), $site->getEvent());

        $qb = $this
            ->getRepository(Movie::class)
            ->createQueryBuilder('m')
            ->innerJoin('m.subscriptions', 's')
            ->join()
            ->andWhere('s.event = :idEvent')
            ->andWhere('s.status = :status')
            ->orderBy('m.title', 'ASC')
            ->setParameters([
                'idEvent' => $site->getEvent()->getId(),
                'status' => Status::SELECTED
            ]);

        $data = $this->getRequest()->getQuery()->toArray();
        if(!empty($data['sub_event'])) {

        }

        $movies = $qb->getQuery()->getResult();

        return new ViewModel([
            'movies' => $movies,
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs(),
            'form' => $formFilter->setData($data)
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

        return new ViewModel([
            'post' => $movie,
            'medias' => $movieMedias,
            'breadcrumbs' => $breadcrumbs,
            'movie_details' => [
                $genre->getName(),
                $color->getName(),
                $format->getName(),
                $duration.' min',
                $movie->getEndDateYear()
            ]
        ]);
    }
}
