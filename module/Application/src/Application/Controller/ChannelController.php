<?php
namespace Application\Controller;

use Application\Entity\Channel\Video;
use Application\Entity\Post\Post;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Application\Entity\Channel\Category;
use Zend\View\Model\ViewModel;

class ChannelController extends SiteController
{
    const SITE_ID = 1;

    public function indexAction()
    {
        /** @var Post $post */
        $post = $this->params('post');

        $cat = [];
        $categories = $this->getRepository(Category::class)->findChannelCategories();
        foreach ($categories as $c) {
            $videos = $this->getRepository(Video::class)->findVideosByCategory($c->getId(), 10);
            $cat[] = [
                'id' => $c->getId(),
                'name' => $c->getName(),
                'slug' => $c->getSlug(),
                'videos' => $videos
            ];
        }

        return new ViewModel([
            'post' => $post,
            'categories' => $cat
        ]);
    }

    public function categoryAction()
    {
        $categorySlug = $this->params('slug');
        if(!$categorySlug) {
            //tela de erro 404
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $category = $this->getRepository(Category::class)->findOneBy(['slug'=>$categorySlug]);
        $videos = $this->getRepository(Video::class)->findVideosByCategory($category->getId());

        return new ViewModel([
            'category' => $category,
            'videos' => $videos
        ]);
    }

    public function videoAction()
    {
        $videoId = $this->params('id');
        if(!$videoId) {
            //tela de erro 404
            $this->getResponse()->setStatusCode(404);
            return;
        }

        $video = $this->getRepository(Video::class)->find($videoId);

        return new ViewModel([
            'video' => $video
        ]);
    }
}
