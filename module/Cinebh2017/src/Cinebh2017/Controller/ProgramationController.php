<?php
namespace Cinebh2017\Controller;

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
    const SITE_ID = 8;

    public function indexAction()
    {
        $post = $this->params('post');
        return new ViewModel([
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs(),
            'lg' => $this->params('locale')=='pt' ? "" : "Ing"
        ]);
    }

    public function filmesAction()
    {
        $post = $this->params('post');
        return new ViewModel([
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs(),
            'lg' => $this->params('locale')=='pt' ? "" : "Ing"
        ]);
    }

    public function artesAction()
    {
        $post = $this->params('post');
        return new ViewModel([
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs(),
            'lg' => $this->params('locale')=='pt' ? "" : "Ing"
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
            'lg' => $this->params('locale')=='pt' ? "" : "Ing"
        ]);
    }

    public function seminariosAction()
    {
        $post = $this->params('post');
        return new ViewModel([
            'post' => $post,
            'breadcrumbs' => $post->getBreadcrumbs(),
            'lg' => $this->params('locale')=='pt' ? "" : "Ing"
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
            'lg' => $this->params('locale')=='pt' ? "" : "Ing"
        ]);
    }

    public function seminarioAction()
    {
    }
}
