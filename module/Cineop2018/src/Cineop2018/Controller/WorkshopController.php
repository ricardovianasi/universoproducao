<?php
namespace Cineop2018\Controller;

use Application\Controller\SiteController;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Application\Entity\Site\Site;
use Application\Entity\Workshop\Workshop;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class WorkshopController extends SiteController
{
    const SITE_ID = 12;

    public function indexAction()
    {
        $post = $this->params('post');
        $site = $this->getRepository(Site::class)->find(self::SITE_ID);

        $qb = $this->getRepository(Workshop::class)->createQueryBuilder('w');
        $qb->innerJoin('w.registration', 'r')
            ->innerJoin('r.events', 'e')
            ->andWhere('e.id = :idEvent')
            ->setParameter('idEvent', $site->getEvent()->getId());

        $workshopList = $qb->getQuery()->getResult();

        return new ViewModel([
            'workshopList' => $workshopList,
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs()
        ]);
    }

    public function detailsAction()
    {
        $id = $this->params()->fromRoute('id');
        if(!$id) {
            //erro, slug não definido
        }

        $workshop = $this->getRepository(Workshop::class)->find($id);

        if(!$workshop) {
            //não achou a notícia
        }

        $breadcrumbs = [
            ['oficina' => 'Oficinas'],
        ];

        return new ViewModel([
            'post' => $workshop,
            'breadcrumbs' => $breadcrumbs
        ]);
    }
}
