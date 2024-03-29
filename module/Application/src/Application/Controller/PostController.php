<?php
namespace Application\Controller;

use Application\Controller\SiteController;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Application\Entity\Site\SiteMeta;
use Zend\View\Model\ViewModel;

class PostController extends SiteController
{
    const SITE_ID = 1;

    protected $breadcrumbs = [];

    public function indexAction()
    {
        $viewModel = new ViewModel();

        $slug = $this->params()->fromRoute('slug');
        $slug = trim($slug, '/');
        $slug = explode('/', $slug);

        $post = $this->getRepository(Post::class)->findOneBy([
            'status' => PostStatus::PUBLISHED,
            'slug' => end($slug),
            'site' => self::SITE_ID
        ]);

        if(!$post) {
            //tela de erro 404
            $this->getResponse()->setStatusCode(404);
            return;
        }

        //Custon Action
        if($post->hasMeta(SiteMeta::CUSTOM_POST_ACTION)) {
            $customAction = $post->getMeta(SiteMeta::CUSTOM_POST_ACTION);
            $customAction = explode(':', $customAction);

            return $this->forward()->dispatch($customAction[0], [
                'action' => $customAction[1],
                'post' => $post
            ]);
        }

        $viewModel->breadcrumbs = $post->getBreadcrumbs();
        $viewModel->post = $post;
        return $viewModel;
    }

    public function newsletterAction()
    {
        return [
            'breadcrumbs' => [
                ['newsletter' => 'Newsletter']
            ]
        ];
    }

    public function searchAction()
    {
        $post = new Post();
        $post->setTitle("Busca");
        return [
            'breadcrumbs' => [
                ['busca' => 'Busca']
            ],
            'post' => $post
        ];
    }

    public function sitemapAction()
    {
        $this->layout("layout/xml");
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        $viewModel->setTemplate(null);
        return $viewModel;
    }
}
