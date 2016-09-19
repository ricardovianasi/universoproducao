<?php
namespace Cinebh\Controller;

use Application\Controller\SiteController;
use Application\Entity\Banner\Banner;
use Application\Entity\Gallery\Gallery;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Application\Entity\Programation;
use Zend\View\Model\ViewModel;

class IndexController extends SiteController
{
    const SITE_ID = 4;

    public function indexAction()
    {
        //banner
        $bannerImages = $this->getRepository(Banner::class)->findBy(['site' => self::SITE_ID], ['order'=>'ASC'], 5);

        //news
        $news = $this->getRepository(Post::class)->findBy(
            ['type'=>PostType::NEWS, 'status'=>PostStatus::PUBLISHED],
            ['postDate'=>'DESC'],
            self::SITE_ID
        );

        //programation
        $program = $this->getRepository(Programation\Highlight::class)->findBy(
            ['site' => self::SITE_ID, 'isHighlight' => 0],
            ['position'=>'ASC']
        );
        $programHighlight = $this->getRepository(Programation\Highlight::class)->findOneBy(
            ['site' => self::SITE_ID, 'isHighlight' => 1]
        );

        //gallery
        $gallery = $this->getRepository(Gallery::class)->findBy(
            ['site' => self::SITE_ID],
            ['order'=>'ASC']
        );

        $guides = $this->getRepository(Post::class)->findBy(
            ['site' => self::SITE_ID, 'type' => PostType::GUIDE, 'status' => PostStatus::PUBLISHED],
            ['order'=>'ASC']
        );

        return new ViewModel([
            'bannerImages' => $bannerImages,
            'news' => $news,
            'programHighlight' => $programHighlight,
            'program' => $program,
            'gallery' => $gallery,
            'guides' => $guides
        ]);
    }
}
