<?php
namespace Application\Controller;

use Application\Controller\SiteController;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Application\Entity\Registration\Registration;
use Zend\View\Model\ViewModel;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use Zend\Paginator\Paginator;

class RegistrationController extends SiteController
{
    const SITE_ID = 1;

    public function indexAction()
    {
        $post = $this->params('post');

        $registrations  = $this->getRepository(Registration::class)->findBy(
            ['status'=>1],
            ['position'=>'ASC', 'startDate'=>'DESC', 'id'=>'DESC']
        );

        return new ViewModel([
            'post' => $post,
            'registrations' => $registrations,
            'breadcrumbs' => $post->getBreadcrumbs()
        ]);
    }
}
