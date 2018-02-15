<?php
namespace MostratiradentesSp\Controller;

use Application\Controller\SiteController;
use Application\Entity\Banner\Banner;
use Application\Entity\Eufacoamostra;
use Application\Entity\Gallery\Gallery;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Application\Entity\Programation;
use Application\Entity\Tv\Tv;
use Zend\View\Model\ViewModel;

class IndexController extends SiteController
{
    const SITE_ID = 11;

    public function indexAction()
    {
        $locale = $this->params('locale','pt');

        //banner
        $bannerImages = $this->getRepository(Post::class)->findBy(
            [
                'site' => self::SITE_ID,
                'type' => PostType::BANNER,
            ],
            ['order'=>'ASC'], 5);

        //news
        $qb = $this->getRepository(Post::class)->findNewsQb(self::SITE_ID);
        $qb->orderBy('n.postDate', 'DESC');
        $qb->setMaxResults(4);
        $news = $qb->getQuery()->getResult();

        //programation
        $program = $this->getRepository(Programation\Highlight::class)->findBy(
            ['site' => self::SITE_ID, 'isHighlight' => 0],
            ['position'=>'ASC']
        );
        $programHighlight = $this->getRepository(Programation\Highlight::class)->findOneBy(
            ['site' => self::SITE_ID, 'isHighlight' => 1]
        );

        //gallery
        $gallery = $this->getRepository(Post::class)->findBy(
            [
                'site' => self::SITE_ID,
                'type' => PostType::GALLERY
            ],
            ['order'=>'ASC']
        );

        $videos = $this->getRepository(Tv::class)->findBy(['site' => self::SITE_ID], [
            'date' => 'DESC'
        ]);

        $guides = $this->getRepository(Post::class)->findBy(
            [
                'site' => self::SITE_ID,
                'type' => PostType::GUIDE,
                'status' => PostStatus::PUBLISHED,
                'language' => $locale
            ],
            ['order'=>'ASC']
        );

        $eufacoamostra = $this->getRepository(Eufacoamostra::class)->findBy(['site' => self::SITE_ID], [], 24);

        return new ViewModel([
            'bannerImages' => $bannerImages,
            'news' => $news,
            'programHighlight' => $programHighlight,
            'program' => $program,
            'gallery' => $gallery,
            'guides' => $guides,
            'videos' => $videos,
            'eufacoamostra' => $eufacoamostra
        ]);
    }
}
