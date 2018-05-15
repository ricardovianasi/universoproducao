<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 28/12/2017
 * Time: 20:37
 */

namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class AdminNavigation extends AbstractHelper
{
    private $navigation;
    private $escapeHtml;

    private $authService;

    public function __construct($authService = null)
    {
        $this->authService = $authService;
    }

    public function __invoke($container=null)
    {
        $this->navigation = $this->getView()->plugin('navigation');
        $this->escapeHtml = $this->getView()->plugin('escapeHtml');

        if(!$container)
            return $this;

        $format = '<ul 
                    class="page-sidebar-menu  page-header-fixed" 
                    data-keep-expanded="false" 
                    data-auto-scroll="true" 
                    data-slide-speed="200" 
                    style="padding-top: 20px">
            <li class="sidebar-toggler-wrapper hide">
                <div class="sidebar-toggler"> </div>
            </li>
            %s
        </ul>';

        return sprintf($format, $this->renderItems($container));
    }

    public function renderItems($items, $sub=false)
    {
        $html = '';
        foreach ($items as $page) {
            $html .= $this->renderItem($page);
        }

        if($sub) {
            $html = '<ul class="sub-menu">'.$html.'</ul>';
        }

        return $html;

    }

    public function renderItem($item)
    {
        $html = '';

        $user = $this->authService->getIdentity();
        if($user) {
            if($user->getEmail() == 'brasilcinemundi@brasilcinemundi.com.br') {
                if($item->get('brasilcinemundi') != true) {
                    return '';
                }
            } elseif($user->getEmail() == 'projetoseducativos@projetoseducativos.com.br') {
                if($item->get('projetoseducativos') != true) {
                    return '';
                }
            } elseif($user->getEmail() == 'workshop@workshop') {
                if($item->get('workshop') != true) {
                    return '';
                }
            }
        }

        if (!$this->getNavigation()->accept($item)) return;

        if ($item->get("separator") === true) {
            return '<li class="divider"></li>';
        }

        if($item->get('heading')) {
            $html.= '<li class="heading">
                        <h3 class="uppercase">
                        '.$item->getLabel().'
                        </h3>
                    </li>';

            return $html;
        }

        $html.= '<li class="nav-item '.($item->isActive(true) ? 'active' : '').$item->getClass().'">';

        $class = $item->hasPages() ? 'nav-link nav-toggle' : 'nav-link';
        $href = $item->hasPages() ? 'javascript:;' : $item->getHref();
        $target = $item->getTarget() ? $item->getTarget() : '';

        $html.= "<a class='$class' target='$target' href='$href'>";

        if($item->get('icon')) {
            $html.= '<i class="'.$item->get('icon').'"></i>';
        }

        $html.= '<span class="title">'.$item->getLabel().'</span>';

        if($item->isActive(true)) {
            $html .= '<span class="selected"></span>';
        }

        if($item->hasPages()) {
            $html.= '<span class="arrow"></span>';
        }

        $html.= '</a>';

        if($item->hasPages()) {
            $html.= $this->renderItems($item->getPages(), true);
        }

        $html.= '</li>';

        return $html;
    }

    /**
     * @return mixed
     */
    public function getNavigation()
    {
        return $this->navigation;
    }

    /**
     * @param mixed $navigation
     */
    public function setNavigation($navigation)
    {
        $this->navigation = $navigation;
    }

    /**
     * @return mixed
     */
    public function getEscapeHtml()
    {
        return $this->escapeHtml;
    }

    /**
     * @param mixed $escapeHtml
     */
    public function setEscapeHtml($escapeHtml)
    {
        $this->escapeHtml = $escapeHtml;
    }
}