<?php
namespace Application\Controller;

use Application\Entity\Banner\Banner;
use Zend\View\Model\ViewModel;

class IndexController extends SiteController
{
    const SITE_ID = 1;

    public function indexAction()
    {
        //banner
        $bannerImages = $this->getRepository(Banner::class)->findBy(['site' => self::SITE_ID], ['order'=>'ASC']);

        return new ViewModel([
            'bannerImages' => $bannerImages,
        ]);
    }
}
