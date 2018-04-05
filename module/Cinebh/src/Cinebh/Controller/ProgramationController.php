<?php
namespace Cinebh\Controller;

use Application\Controller\SiteController;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class ProgramationController extends SiteController
{
    const SITE_ID = 13;

    public function indexAction()
    {
        $post = $this->params('post');
        return new ViewModel([
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs(),
            'lg' => $this->params('locale')
        ]);
    }

    public function filmesAction()
    {
        $post = $this->params('post');
        return new ViewModel([
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs(),
            'lg' => $this->params('locale')
        ]);
    }

    public function artesAction()
    {
        $post = $this->params('post');
        return new ViewModel([
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs(),
            'lg' => $this->params('locale')
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
            'idMovie' => $filmeId,
            'lg' => $this->params('locale')
        ]);
    }

    public function seminariosAction()
    {
        $post = $this->params('post');
        return new ViewModel([
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs(),
            'lg' => $this->params('locale')
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
            'seminarId' => $seminarId,
            'lg' => $this->params('locale')
        ]);
    }

    public function seminarioAction()
    {
    }
}
