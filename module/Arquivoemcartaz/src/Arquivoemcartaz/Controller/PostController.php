<?php
namespace Arquivoemcartaz\Controller;

use Application\Controller\SiteController;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Zend\View\Model\ViewModel;

class PostController extends SiteController
{
    const SITE_ID = 3;

    public function indexAction()
    {
        $viewModel = new ViewModel();

        $slug = $this->params()->fromRoute('slug');
        $slug = rtrim($slug, '/');
        $slug = explode('/', $slug);

        $post = $this->getRepository(Post::class)->findOneBy([
            'status' => PostStatus::PUBLISHED,
            'slug' => end($slug),
            'site' => self::SITE_ID
        ]);

        if(!$post) {
            //tela de erro 404
        }

        $viewModel->post = $post;
        return $viewModel;
    }
}
