<?php
namespace Application\Controller;

use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Zend\View\Model\ViewModel;

class ProjectController extends SiteController
{
    const SITE_ID = 1;

    public function indexAction()
    {
        /** @var Post $post */
        $post = $this->params('post');
        $projects = $post->getChildren();

        return new ViewModel([
            'projects' => $projects,
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
