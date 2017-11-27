<?php
namespace Mostratiradentes\Controller;

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
    const SITE_ID = 10;

    public function indexAction()
    {
        $post = $this->params('post');
        return new ViewModel([
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs()
        ]);
    }

    public function filmesAction()
    {
        $post = $this->params('post');
        return new ViewModel([
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs()
        ]);
    }

    public function artesAction()
    {
        $post = $this->params('post');
        return new ViewModel([
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs()
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
            'idMovie' => $filmeId
        ]);
    }

    public function seminariosAction()
    {
        $post = $this->params('post');
        return new ViewModel([
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs()
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
            'seminarId' => $seminarId
        ]);
    }

    public function arteAction()
    {
        $arteId = $this->params('id');

        $breadcrumbs = [
            ['programacao' => 'Programação'],
            ['filmes' => 'Seminários']
        ];
        return new ViewModel([
            'breadcrumbs' => $breadcrumbs,
            'arteId' => $arteId
        ]);
    }
}
