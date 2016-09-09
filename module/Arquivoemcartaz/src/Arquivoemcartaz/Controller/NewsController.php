<?php
namespace Arquivoemcartaz\Controller;

use Application\Controller\SiteController;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class NewsController extends SiteController
{
    const SITE_ID = 3;

    public function indexAction()
    {
        $post = $this->params('post');
        $page = $this->params()->fromQuery('pagina', 1);

        $qb = $this->getRepository(Post::class)->createQueryBuilder('p');
        $qb->andWhere('p.status = :status')
            ->andWhere('p.type = :type')
            ->orderBy('p.postDate', 'DESC')
            ->setParameters([
                'status' => PostStatus::PUBLISHED,
                'type' => PostType::NEWS
            ]);

        $adapter = new DoctrinePaginator(new ORMPaginator($qb));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'newsList' => $paginator,
            'post' => $post
        ]);
    }

    public function newsAction()
    {

        $slug = $this->params()->fromRoute('slug');
        if(!$slug) {
            //erro, slug não definido
        }

        $news = $this->getRepository(Post::class)->findOneBy([
            'type' => PostType::NEWS,
            'status' => PostStatus::PUBLISHED,
            'slug' => $slug
        ]);

        if(!$news) {
            //não achou a notícia
        }

        $view = new ViewModel([
            'post' => $news
        ]);
        $view->setTemplate('arquivoemcartaz/post/index.phtml');
        return $view;
    }
}
