<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 14/03/2016
 * Time: 19:17
 */

namespace Admin\View\Helper;


use Zend\Form\View\Helper\AbstractHelper;

class AdminMenuPages extends AbstractHelper
{
	public $conteinerFormat = '<div class="icheck-list">%s</div>';

	public $listFormat = '<ol class="menu-pages-list">%s</ol>';

	public $itemFormat = '<li class="menu-pages-item">%s</li>';

	public function __invoke($pages=[], $ignoreChild=false)
	{
		return sprintf(
			$this->conteinerFormat,
			$this->renderItems($pages, $ignoreChild)
		);
	}

	public function renderItems($pages=[], $ignoreChild=false)
	{
		$markup = '';
		foreach($pages as $p) {
			$markup.= $this->renderItem($p, $ignoreChild);
		}

		return sprintf($this->listFormat, $markup);
	}

	public function renderItem($page, $ignoreChild=false)
	{
		$marckup = '<label>
				<input type="checkbox" class="icheck" name="pages['.$page->getId().']" value="'.$page->getId().'">
				'.$page->getTitle().'</label>';

		if(!$ignoreChild &&	 $page->hasChildren()) {
			$marckup.= $this->renderItems($page->getChildren());
		}

		return sprintf($this->itemFormat, $marckup);
	}

}