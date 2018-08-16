<?php
namespace Cinebh\Controller;

use Application\Controller\SiteController;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Application\Entity\Seminar\Category;
use Application\Entity\Seminar\Debate;
use Application\Entity\Site\Site;
use Application\Entity\Workshop\Workshop;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class SeminarController extends SiteController
{
    const SITE_ID = 13;

    public function indexAction()
    {
        $post = $this->params('post');
        $site = $this->getRepository(Site::class)->find(self::SITE_ID);

        $catId = $this->params()->fromQuery('category', null);

        $qb = $this->getRepository(Debate::class)->createQueryBuilder('w');
        $qb->andWhere('w.event = :idEvent')
            ->setParameter('idEvent', $site->getEvent()->getId());
        if($catId) {
            $qb->andWhere('w.category = :idCategory')
                ->setParameter('idCategory', $catId);
        }

        $list = $qb->getQuery()->getResult();
        $debates = [];
        foreach ($list as $l) {
            if($l->getPrograming()) {
                $prog = $l->getPrograming();
                $prog = current($prog);
                $d = $prog->getDate()?$prog->getDate()->format('dmY'):"";
                $d.= $prog->getStartTime()->format('His');
                $d.= $l->getId();

                $debates[$d] = $l;
            }
        }
        sort($debates);

        $categories = $this->getRepository(Category::class)->findAll();

        return new ViewModel([
            'list' => $list,
            'debates' => $debates,
            'post' => $post,
            'categories' => $categories,
            'category' => $catId,
            'breadcrumbs' => $post->getBreadcrumbs()
        ]);
    }

    public function detailsAction()
    {
        $id = $this->params()->fromRoute('id');
        if(!$id) {
            //erro, slug não definido
        }

        $item = $this->getRepository(Debate::class)->find($id);

        if(!$item) {
            //não achou a notícia
        }

        $breadcrumbs = [
            ['seminario' => 'Programa de formação'],
        ];

        return new ViewModel([
            'post' => $item,
            'breadcrumbs' => $breadcrumbs
        ]);
    }
}
