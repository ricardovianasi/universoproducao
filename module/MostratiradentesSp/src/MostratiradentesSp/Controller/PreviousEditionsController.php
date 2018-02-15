<?php
namespace MostratiradentesSp\Controller;

use Application\Controller\SiteController;
use Application\Entity\Event\Event;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Site\SiteMeta;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Part;
use Zend\View\Model\ViewModel;

class PreviousEditionsController extends SiteController
{
    const SITE_ID = 11;

    protected $breadcrumbs = [];

    public function indexAction()
    {
        $viewModel = new ViewModel();
        $post = $this->params('post');

        $events = $this->getRepository(Event::class)->findBy([
            'type' => 'mostratiradentes'
        ], ['edition' => 'DESC']);

        $viewModel->post = $post;
        $viewModel->breadcrumbs = $post->getBreadcrumbs();
        $viewModel->events = $events;

        return $viewModel;
    }

    public function editionAction()
    {
        $post = new Post();

        $idEdition = $this->params('id');
        $edition = $this->getRepository(Event::class)->findOneBy([
            'id' => $idEdition,
            'type' => 'mostratiradentes'
        ]);

        $post->setTitle($edition->getFullName());
        $post->setContent($edition->getDescription());

        $breadcrumbs = [
            ['a-mostra' => 'A Mostra'],
            ['edicoes-anteriores' => 'Edições Anteriores'],
            [$idEdition => $edition->getFullName()]
        ];

        return new ViewModel([
            'post' => $post,
            'breadcrumbs' => $breadcrumbs
        ]);
    }
}
