<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 09/09/2016
 * Time: 13:10
 */

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Head extends AbstractHelper
{
    public function __invoke()
    {
        $view = $this->getView()->plugin('view_model')->getCurrent()->getChildren();
        $viewModel = $view[0];

        $post = $viewModel->post;
        if($post) {
            $headMeta = $this->getView()->plugin('headMeta');
            $headTitle = $this->getView()->plugin('headTitle');

            $headTitle->append($post->getTitle());
        }
    }
}