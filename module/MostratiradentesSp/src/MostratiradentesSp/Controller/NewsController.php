<?php
namespace MostratiradentesSp\Controller;

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
    const SITE_ID = 11;

    public function indexAction()
    {
        $post = $this->params('post');
        $page = $this->params()->fromQuery('pagina', 1);

        $qb = $this->getRepository(Post::class)->findNewsQb(self::SITE_ID);
        $qb->orderBy('n.postDate', 'DESC');
        $adapter = new DoctrinePaginator(new ORMPaginator($qb, false));
        $paginator = new Paginator($adapter);
        $paginator->setDefaultItemCountPerPage(10);
        $paginator->setCurrentPageNumber($page);

        return new ViewModel([
            'newsList' => $paginator,
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs()
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

        $breadcrumbs = [
            ['noticias' => 'Notícias'],
            [$news->getSlug() => $news->getTitle()]
        ];

        return new ViewModel([
            'post' => $news,
            'breadcrumbs' => $breadcrumbs
        ]);
    }
}
