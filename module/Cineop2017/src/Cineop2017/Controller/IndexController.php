<?php
namespace Cineop2017\Controller;

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
    const SITE_ID = 7;

    public function indexAction()
    {
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
                'status' => PostStatus::PUBLISHED
            ],
            ['order'=>'ASC']
        );

        $eufacoamostra = $this->getRepository(Eufacoamostra::class)->findBy(['site' => self::SITE_ID], [], 24);

        return new ViewModel([
            'bannerImages' => $bannerImages,
            'news' => $news,
            'program' => $program,
            'gallery' => $gallery,
            'guides' => $guides,
            'videos' => $videos,
            'eufacoamostra' => $eufacoamostra
        ]);
    }

    public function indexSpAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setVariables(array('key' => 'value'))
            ->setTerminal(true);

        return $viewModel;
    }
}
