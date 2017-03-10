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

    }

    public function newsletterAction()
    {
        return [
            'breadcrumbs' => [
                ['newsletter' => 'Newsletter']
            ]
        ];
    }
}
