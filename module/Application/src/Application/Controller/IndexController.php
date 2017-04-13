<?php
namespace Application\Controller;

use Application\Entity\Banner\Banner;
use Application\Entity\Channel\Video;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostType;
use Zend\View\Model\ViewModel;

class IndexController extends SiteController
{
    const SITE_ID = 1;

    public function indexAction()
    {
        //banner
        $bannerImages = $this->getRepository(Post::class)->findBy(
            [
                'site' => self::SITE_ID,
                'type' => PostType::BANNER,
            ],
            ['order'=>'ASC'], 5);

        $qb = $this->getRepository(Post::class)->findNewsQb(self::SITE_ID);
        $qb->orderBy('n.postDate', 'DESC');
        $qb->setMaxResults(3);
        $news = $qb->getQuery()->getResult();

        //gallery
        $gallery = $this->getRepository(Post::class)->findBy(
            [
                'site' => self::SITE_ID,
                'type' => PostType::GALLERY
            ],
            ['order'=>'asc'], 8
        );

        $videos = $this->getRepository(Video::class)->findBy([], ['date'=>'desc'], 10);

        return new ViewModel([
            'bannerImages' => $bannerImages,
            'news' => $news,
            'gallery' => $gallery,
            'videos' => $videos
        ]);
    }
}
