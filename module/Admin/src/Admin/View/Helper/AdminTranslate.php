<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 17/10/2016
 * Time: 09:45
 */

namespace Admin\View\Helper;

use Application\Entity\Post\Post;
use Application\Entity\Post\PostMeta;
use Application\Entity\Site\Site;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\RequestInterface;
use Zend\View\Helper\AbstractHelper;

class AdminTranslate extends AbstractHelper
{
    const QUERY_PARAM_LANG = 'lang';
    const QUERY_PARAM_TRANSLATE_FROM = 'translate_from';

    private $request;

    protected $event;

    private $em;

    protected $routeName;

    protected $postId;

    protected $translateFromPostId;

    protected $siteId;

    public function __construct(RequestInterface $request, MvcEvent $event, $em)
    {
        $this->request = $request;
        $this->event = $event;
        $this->em = $em;
    }

    public function __invoke($siteId, $post, $routeName=null)
    {
        $site = $this->getSite($siteId);
        if(!($site && $site->hasLanguages())) {
            return "";
        }
        $this->siteId = $site->getId();

        $this->routeName = $routeName;

        $translateLang = $this->fromQuery('lang');
        if(!$translateLang && $post->getLanguage()) {
            $translateLang = $post->getLanguage()->getId();
        } else {
            $translateLang = 'pt';
        }

        $translateFromId = $this->fromQuery('translate_from');
        if(!$translateFromId && $post->hasMeta(PostMeta::TRANSLATE_FROM)) {
            $translateFromId = $post->getMeta(PostMeta::TRANSLATE_FROM);
        }
        $this->translateFromPostId = $translateFromId;

        if($post->getId()) {
            $this->postId = $post->getId();
        }

        $body = "";
        $body.= "<div class='form-group'><label>O idioma desta página</label>".$this->renderLanguages($site->getLanguages(), $translateLang)."</div>";

        if($this->translateFromPostId) {
            $translateFrom = $this->getEntityManager()->getRepository(Post::class)->find($this->translateFromPostId);
            if ($translateFrom) {
                $body .= "<div class='form-group'>
                    <label>Esta é uma tradução de</label>
                    <input type='text' name='translate-for' readonly='readonly' class='form-control' value='" . $translateFrom->getTitle() . "'>
                    <input type='hidden' name='meta[_translate_from]' value='" . $translateFrom->getId() . "'>
                </div>";
            }
        }

        $body.="<hr /><h4>Traduções</h4>";
        $body.= $this->renderTranslateOptions($site->getLanguages(), $translateLang);

        return '<div class="portlet light bordered grey-cararra post-sidebar post-sidebar-translate">
                    <div class="portlet-title">
                        <div class="caption"><i class="fa fa-flag-checkered"></i>Tradução</div>
                    </div>
                    <div class="portlet-body">
                        <div class="form-body">'.$body.'</div>
                    </div>
                </div>';
    }

    /**
     * @param $id
     * @return null|Site
     */
    protected function getSite($id)
    {
        return $this->getEntityManager()->getRepository(Site::class)->find($id);
    }

    protected function renderLanguages($siteLangues, $default=false)
    {
        $options = '';
        foreach ($siteLangues as $lang) {
            $options.= "<option ".($lang->getId()==$default ? "selected='selected'" : "")." value='".$lang->getId()."'>".$lang->getName()."</option>";
        }
        return "<select name='language' id='language' class='form-control'>$options</select>";
    }

    protected function renderTranslateOptions($languages, $ignore)
    {
        $options = "";
        foreach ($languages as $lang) {
            if($lang->getId() == $ignore) {
                continue;
            }

            if($this->hasTranslatedPost($lang->getId())) {
                $actionUrl = $this->view->url($this->routeName, ['action'=>'update', 'site'=>$this->siteId, 'id'=>$this->translateFromPostId]);
                $classIcon = "fa fa-pencil";
            } else {
                $queryParans = [
                    self::QUERY_PARAM_LANG => $lang->getId(),
                    self::QUERY_PARAM_TRANSLATE_FROM => $this->postId
                ];
                $actionUrl = $this->view->url($this->routeName, ['action'=>'create', 'site'=>$this->siteId]).'?'.http_build_query($queryParans);
                $classIcon = "fa fa-plus";
            }

            $options.= "<div class='form-group'>".$lang->getName()." <a href='$actionUrl' class='btn btn-default pull-right'><i class='$classIcon'></i></a></div>";
            return $options;
        }
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->em;
    }

    public function setEntityManager($em)
    {
        $this->em = $em;
    }

    public function fromPost($param = null, $default = null)
    {
        if ($param === null)
        {
            return $this->request->getPost($param, $default)->toArray();
        }

        return $this->request->getPost($param, $default);
    }

    public function fromQuery($param = null, $default = null)
    {
        if ($param === null)
        {
            return $this->request->getQuery($param, $default)->toArray();
        }

        return $this->request->getQuery($param, $default);
    }

    public function fromRoute($param = null, $default = null)
    {
        if ($param === null)
        {
            return $this->event->getRouteMatch()->getParams();
        }

        return $this->event->getRouteMatch()->getParam($param, $default);
    }

    protected function hasTranslatedPost($lang)
    {
        $qb = $this->getEntityManager()->getRepository(Post::class)->createQueryBuilder('q');
        $qb->select('COUNT(q.id)')
            ->leftJoin('q.meta', 'm')
            ->andWhere('q.site = :idSite')
            ->andWhere('q.language = :lang')
            ->andWhere('((m.key = :metaKey and m.value = :metaValue) OR q.id = :id)')
            ->setParameters([
                'idSite' => $this->siteId,
                'id' => $this->translateFromPostId,
                'lang' => $lang,
                'metaKey' => PostMeta::TRANSLATE_FROM,
                'metaValue' => $this->translateFromPostId
            ]);

        return $qb->getQuery()->getSingleScalarResult();
    }
}