<?php
namespace Mostratiradentes\Controller;

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
    const SITE_ID = 10;
    const SEPARATOR = ' • ';

    public function indexAction()
    {
        $programing = [];
        $post = $this->params('post');
        $site = $this->getRepository(Site::class)->find(self::SITE_ID);

        $qb = $this
            ->getRepository(Programing::class)
            ->createQueryBuilder('p')
            ->andWhere('p.parent is NULL')
            ->addOrderBy('p.date', 'ASC')
            ->addOrderBy('p.startTime', 'ASC');

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
            if ($prog->getEndTime()) {
                $timeLabel =
                    $prog->getStartTime()->format('H\hi')
                    . ' às '
                    . $prog->getEndTime()->format('H\hi');
            } else {
                $timeLabel = $prog->getStartTime()->format('H\hi');
            }

            $events = [];

            //Tipo do evento
            $titleItem = "";
            if ($prog->getType() == Type::MOVIE || $prog->getType() == Type::SESSION) {
                $titleItem = "Filme";
                if ($prog->getSubEvent()) {
                    $titleItem .= self::SEPARATOR . $prog->getSubEvent()->getName();
                }

                if ($prog->getType() == Type::SESSION && $prog->hasMeta(Meta::SESSION_TITLE)) {
                    $titleItem .= self::SEPARATOR . $prog->hasMeta(Meta::SESSION_TITLE)->getValue();
                    foreach ($prog->getChildren() as $sess) {
                        $events[] = [
                            'title' => $sess->getObject()->getTitle()." | <span class=\"programing-direction\">Direção: ".$sess->getObject()->getDirection()."</span>"
                        ];
                    }
                } else {
                    $events[] = [
                        'title' => $prog->getObject()->getTitle()." | <span class=\"programing-direction\">Direção: ".$prog->getObject()->getDirection()."</span>"
                    ];
                }

                if($prog->hasMeta(Meta::NATIONAL_PREMIERE)) {
                    $titleItem.= self::SEPARATOR . 'pré-estreia-nacional';
                } elseif ($prog->hasMeta(Meta::WORLD_PREMIERE)) {
                    $titleItem.= self::SEPARATOR . 'pré-estreia-mundial';
                }

            } elseif ($prog->getType() == Type::SEMINAR_DEBATE) {
                $titleItem = "Seminário" . self::SEPARATOR . $prog->getObject()->getThematic()->getName();
                $events[] = [
                    'title' => $prog->getObject()->getTitle()
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
                    'title' => $prog->getObject()->getTitle()
                ];

            } elseif ($prog->getType() == Type::OPENING) {
                $titleItem = "Abertura";
                $events[] = [
                    'description' => $prog->getObject()->getDescription()
                ];
            } elseif ($prog->getType() == Type::CLOSING) {
                $titleItem = "Encerramento";
                $events[] = [
                    'description' => $prog->getObject()->getDescription()
                ];
            }

            $programing[$dateKey]['items'][] = [
                'time' => $timeLabel,
                'start_time' => $prog->getStartTime(),
                'end_time' => $prog->getEndTime(),
                'type' => $prog->getType(),
                'place' => $prog->getPlace() ? $prog->getPlace()->getName() : "",
                'title' => $titleItem,
                'info' => $prog->hasMeta(Meta::ADDITIONAL_INFO) ? $prog->hasMeta(Meta::ADDITIONAL_INFO)->getValue() : "",
                'events' => $events
            ];
        }



        return new ViewModel([
            'programing' => $programing,
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs()
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
