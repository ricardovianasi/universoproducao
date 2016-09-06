<?php
namespace Arquivoemcartaz\Controller;

use Application\Controller\SiteController;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Zend\View\Model\ViewModel;

class NewsController extends SiteController
{
    const SITE_ID = 3;

    public function indexAction()
    {
        return [];
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
