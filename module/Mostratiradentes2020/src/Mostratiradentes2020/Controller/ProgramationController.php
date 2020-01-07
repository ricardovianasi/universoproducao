<?php
namespace Mostratiradentes2020\Controller;

use Admin\Form\Movie\MovieProgramingForm;
use Application\Controller\SiteController;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Application\Entity\Programing\Meta;
use Application\Entity\Programing\Programing;
use Application\Entity\Programing\Type;
use Application\Entity\Site\Site;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class ProgramationController extends SiteController
{
    const SITE_ID = 19;
    const SEPARATOR = ' • ';

    public function indexAction()
    {
        $programing = [];
        $post = $this->params('post');
        $site = $this->getRepository(Site::class)->find(self::SITE_ID);

        $formFilter = new MovieProgramingForm($this->getEntityManager(), $site->getEvent());
        $data = $this->getRequest()->getQuery()->toArray();

        //Filtro do dia
        $daysEvents = $this
            ->getRepository(Programing::class)
            ->createQueryBuilder('p')
            ->select('p.date')
            ->andWhere('p.event = :idEvent')
            ->andWhere('p.date is not null')
            ->setParameter('idEvent', $site->getEvent()->getId())
            ->groupBy('p.date')
            ->orderBy('p.date')
            ->getQuery()
            ->getScalarResult();

        $qb = $this
            ->getRepository(Programing::class)
            ->createQueryBuilder('p')
            ->andWhere('p.parent is NULL')
            ->andWhere('p.type != :type')
            ->andWhere('p.event = :idEvent')
            ->setParameter('idEvent', $site->getEvent()->getId())
            ->setParameter('type', Type::WORKSHOP)
            ->addOrderBy('p.date', 'ASC')
            ->addOrderBy('p.order', 'ASC')
            ->addOrderBy('p.startTime', 'ASC');

        if(!empty($data['sub_event'])) {
            $qb->andWhere('p.subEvent = :idSubEvent')
                ->setParameter('idSubEvent', $data['sub_event']);
        }

        if(!empty($data['place'])) {
            $qb->andWhere('p.place = :place')
                ->setParameter('place', $data['place']);
        }

        if(!empty($data['programing_type'])) {
            $qb->andWhere('p.type = :programing_type')
                ->setParameter('programing_type', $data['programing_type']);
        }

        if(!empty($data['day'])) {
            $day = new \DateTime($data['day']);
            $qb->andWhere('p.date = :date')
                ->setParameter('date', $day);
        }

        $items = $qb->getQuery()->getResult();


        foreach ($items as $prog) {

            /** @var Programing $prog */
            $prog = $prog;
            $dateKey = $prog->getDate()->format('dmY');
            if (!key_exists($dateKey, $programing)) {
                $programing[$dateKey] = [
                    'date' => $prog->getDate(),
                    'items' => []
                ];
            }

            //Hora do evento
            $timeLabel = "";
            if($prog->getStartTime()) {
                if ($prog->getEndTime()) {
                    $timeLabel =
                        $prog->getStartTime()->format('H\hi')
                        . ' às '
                        . $prog->getEndTime()->format('H\hi');
                } else {
                    $timeLabel = $prog->getStartTime()->format('H\hi');
                }
            }

            $events = [];
            $ageRange = "";

            //Tipo do evento
            $titleItem = "";
            if ($prog->getType() == Type::MOVIE || $prog->getType() == Type::SESSION) {

                if($prog->getType() == Type::SESSION) {
                    $titleItem = "Sessão";
                } else {
                    $titleItem = "Filme";
                }
                if ($prog->getSubEvent()) {
                    $titleItem .= self::SEPARATOR . $prog->getSubEvent()->getName();
                }

                if ($prog->getType() == Type::SESSION && $prog->hasMeta(Meta::SESSION_TITLE)) {
                    $titleItem .= self::SEPARATOR . $prog->hasMeta(Meta::SESSION_TITLE)->getValue();
                    foreach ($prog->getChildren() as $sess) {
                        $events[] = [
                            'title' => $sess->getObject()->getTitle()." | <span class=\"programing-direction\">Direção: ".$sess->getObject()->getDirection()."</span>",
                            'id' => $sess->getObject()->getId()
                        ];
                    }
                } else {
                    $events[] = [
                        'title' => $prog->getObject()->getTitle()." | <span class=\"programing-direction\">Direção: ".$prog->getObject()->getDirection()."</span>",
                        'id' => $prog->getObject()->getId()
                    ];
                }

                if($prog->hasMeta(Meta::NATIONAL_PREMIERE)) {
                    $titleItem.= self::SEPARATOR . 'pré-estreia-nacional';
                } elseif ($prog->hasMeta(Meta::WORLD_PREMIERE)) {
                    $titleItem.= self::SEPARATOR . 'pré-estreia-mundial';
                }

            } elseif ($prog->getType() == Type::SEMINAR_DEBATE &&  $prog->getObject()) {
                $titleItem = "Seminário" . self::SEPARATOR . $prog->getObject()->getThematic()->getName();
                $events[] = [
                    'title' => $prog->getObject()->getTitle(),
                    'id' => $prog->getObject()->getId()
                ];
            } elseif ($prog->getType() == Type::WORKSHOP) {
                $titleItem = "Oficina";
                $events[] = [
                    'title' => $prog->getObject()->getName()
                ];
            } elseif ($prog->getType() == Type::ART) {
                $titleItem = "Arte";
                if(!$prog->getObject()) {
                    continue;
                }
                if($prog->getObject()->getCategory()) {
                    $titleItem.=self::SEPARATOR . $prog->getObject()->getCategory()->getName();
                }

                $events[] = [
                    'title' => $prog->getObject()->getTitle(),
                    'id' => $prog->getObject()->getId()
                ];

            } elseif ($prog->getType() == Type::OPENING) {
                $titleItem = "Abertura";
                $events[] = [
                    'description' => $prog->getObject()->getDescription()
                ];
            } elseif ($prog->getType() == Type::CLOSING) {

                if(!$prog->getObject()) {
                    continue;
                }

                $titleItem = "Encerramento";
                $events[] = [
                    'description' => $prog->getObject()->getDescription()
                ];
            } elseif ($prog->getType() == Type::SESSION_SCHOOL) {
                $titleItem = "Sessão cine-escola";
                if($prog->getObject()) {
                    $ageRange = $prog->getObject()->getAgeRange();
                    foreach ($prog->getObject()->getMovies() as $m) {
                        $movie = $m->getMovie();
                        $events[] = [
                            'title' => $movie->getTitle()." | <span class=\"programing-direction\">Direção: ".$movie->getDirection()."</span>",
                            'id' => $movie->getId()
                        ];
                    }
                }
            }

            $programing[$dateKey]['items'][] = [
                'time' => $timeLabel,
                'start_time' => $prog->getStartTime(),
                'end_time' => $prog->getEndTime(),
                'type' => $prog->getType(),
                'place' => $prog->getPlace() ? $prog->getPlace()->getName() : "",
                'title' => $titleItem,
                'age_range' => $ageRange?$ageRange:$prog->getAgeRange(),
                'info' => $prog->hasMeta(Meta::ADDITIONAL_INFO) ? $prog->hasMeta(Meta::ADDITIONAL_INFO)->getValue() : "",
                'events' => $events
            ];
        }



        return new ViewModel([
            'programing' => $programing,
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs(),
            'form' => $formFilter->setData($data),
            'eventDays' => $daysEvents

        ]);
    }

    public function filmesAction()
    {
        $post = $this->params('post');
        return new ViewModel([
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs()
        ]);
    }

    public function artesAction()
    {
        $post = $this->params('post');
        return new ViewModel([
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs()
        ]);
    }

    public function movieAction()
    {
        $filmeId = $this->params('id');

        $breadcrumbs = [
            ['programacao' => 'Programação'],
            ['filmes' => 'Filmes']
        ];
        return new ViewModel([
            'breadcrumbs' => $breadcrumbs,
            'idMovie' => $filmeId
        ]);
    }

    public function seminariosAction()
    {
        $post = $this->params('post');
        return new ViewModel([
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs()
        ]);
    }

    public function seminarAction()
    {
        $seminarId = $this->params('id');

        $breadcrumbs = [
            ['programacao' => 'Programação'],
            ['filmes' => 'Seminários']
        ];
        return new ViewModel([
            'breadcrumbs' => $breadcrumbs,
            'seminarId' => $seminarId
        ]);
    }

    public function arteAction()
    {
        $arteId = $this->params('id');

        $breadcrumbs = [
            ['programacao' => 'Programação'],
            ['filmes' => 'Seminários']
        ];
        return new ViewModel([
            'breadcrumbs' => $breadcrumbs,
            'arteId' => $arteId
        ]);
    }
}
