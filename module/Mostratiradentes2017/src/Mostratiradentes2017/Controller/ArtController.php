<?php
namespace Mostratiradentes2017\Controller;

use Application\Controller\SiteController;
use Application\Entity\Art\Art;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Application\Entity\Seminar\Debate;
use Application\Entity\Site\Site;
use Application\Entity\Workshop\Workshop;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class ArtController extends SiteController
{
    const SITE_ID = 10;

    public function indexAction()
    {
        $post = $this->params('post');
        $site = $this->getRepository(Site::class)->find(self::SITE_ID);

        $qb = $this->getRepository(Art::class)->createQueryBuilder('w');
        $qb->andWhere('w.event = :idEvent')
            ->setParameter('idEvent', $site->getEvent()->getId());

        $list = $qb->getQuery()->getResult();

        return new ViewModel([
            'list' => $list,
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

        $item = $this->getRepository(Art::class)->find($id);

        if(!$item) {
            //não achou a notícia
        }

        $breadcrumbs = [
            ['arte' => 'Artes'],
        ];

        return new ViewModel([
            'post' => $item,
            'breadcrumbs' => $breadcrumbs
        ]);
    }
}
