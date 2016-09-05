<?php
namespace Cineop\View\Helper;

use Application\Entity\Site\Menu\Item;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\View\Helper\AbstractHelper;

class Menu extends AbstractHelper
{
	protected $items;
	protected $urlHelper;

	public function __construct($items, $urlHelper)
	{
		$this->items = $items;
		$this->urlHelper = $urlHelper;
	}

	public function __invoke()
	{
		return $this;
	}

	public function render()
	{
		$markup = '<div id="mainmenu-modal" class="mainmenu">
                <div class="mainmenu__wrapper">
                    <nav class="mainmenu__navigation ">
                    	<div class="mainmenu__navigation-wrapper">
                        <ul class="mainmenu__list">';

		$markup.= $this->renderItens($this->items);

		$markup.= '</ul>
					</div>
                    </nav>
                </div>
            </div>';

		return $markup;
	}

	public function renderItens($items, $isChildren=false)
	{
		$urlHelper = $this->urlHelper;
		$urlBase = $urlHelper('cineop');
		$urlBase = rtrim($urlBase, '/');

		$markup = '';
		foreach ($items as $item)
		{
			$target = $item->getTargetBlank() ? 'target="_black"' : '';
			$label = $item->getLabel() ? $item->getLabel() : $item->getPost()->getTitle();
			$href = $item->isExternalLink() ?
				$item->getExternalUrl() :
				$urlBase . '/' . $item->getPost()->getRelativeUrl();

			$liClass = $aClass = '';
			if(!$isChildren) {
				$liClass = 'mainmenu__list-item--level-1';
				$aClass = 'mainmenu__link--level-1';
			}

			$markup.= '<li class="mainmenu__list-item '.$liClass.'">';
			$markup.= '<a href="'.$href.'" class="mainmenu__link  '.$aClass.'" title="" '.$target.'>'.$label.'</a>';

			if($item->hasChildren()) {
				$markup.= '<ul class="mainmenu__list mainmenu__list--sub">';
				$markup.= $this->renderItens($item->getChildren(), true);
				$markup.= '</ul>';
			}

			$markup.= '</li>';
		}

		return $markup;
	}

	public function renderSiteMap()
	{
		$markup = '<ul class="sitemap__items">';
		$markup.= $this->renderSiteMapItens($this->items);
		
		$markup.= '<li class="sitemap__item ">
					<a href="" class="sitemap__link sitemap__link--level-1"><span>Cinema Sem Fronteiras</span></a>
					<ul class="sitemap__items sitemap__items--sub">
						<li class="sitemap__item"><a class="sitemap__link" href=""><span>Cinema Sem Fronteiras</span></a></li>
						<li class="sitemap__item"><a class="sitemap__link" href=""><span>Mostra Tiradentes</span></a></li>
						<li class="sitemap__item"><a class="sitemap__link" href=""><span>CineOP</span></a></li>
						<li class="sitemap__item"><a class="sitemap__link" href=""><span>Mostra CineBH</span></a></li>
						<li class="sitemap__item"><a class="sitemap__link" href=""><span>Universo Produção</span></a></li>
						<li class="sitemap__item"><a class="sitemap__link" href=""><span>Instituto Universo Cultural</span></a></li>
						<li class="sitemap__item"><a class="sitemap__link" href=""><span>Turma do Pipoca</span></a></li>
					</ul>
				</li>';
		
		$markup .= '</ul>';

		return $markup;

	}

	public function renderSiteMapItens($items, $isChildren=false)
	{
		$urlHelper = $this->urlHelper;
		$urlBase = $urlHelper('cineop');
		$urlBase = rtrim($urlBase, '/');

		$markup = '';
		foreach ($items as $item) {
			$target = $item->getTargetBlank() ? 'target="_black"' : '';
			$label = $item->getLabel() ? $item->getLabel() : $item->getPost()->getTitle();
			$href = $item->isExternalLink() ?
				$item->getExternalUrl() :
				$urlBase . '/' . $item->getPost()->getRelativeUrl();

			$aClass = '';
			if (!$isChildren) {
				$aClass = 'sitemap__link--level-1';
				$href = '#';
			}

			$markup.= '<li class="sitemap__item">';
			$markup.= '<a href="'.$href.'" class="sitemap__link '.$aClass.'" '.$target.'><span>'.$label.'</span></a>';

			if($item->hasChildren()) {
				$markup.= '<ul class="sitemap__items sitemap__items--sub">';
				$markup.= $this->renderSiteMapItens($item->getChildren(), true);
				$markup.= '</ul>';
			}

			$markup.= '</li>';
		}

		return $markup;
	}
}