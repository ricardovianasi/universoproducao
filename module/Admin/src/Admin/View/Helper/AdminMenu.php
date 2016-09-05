<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 06/03/2016
 * Time: 13:01
 */

namespace Admin\View\Helper;

use Application\Entity\Site\Menu\Item;
use Zend\Form\View\Helper\AbstractHelper;

class AdminMenu extends AbstractHelper
{
	/*
	 * Default Options
	 */
	protected $listNodeName = 'ol';

	protected $itemNodeName = 'li';

	protected $rootClass = 'dd';

	protected $listClass = 'dd-list';

	protected $itemClass = 'dd-item';

	protected $handleClass = 'dd-handle';

	protected $collapsedClass = 'dd-collapsed';

	public function __invoke($items = null, $attributes = null)
	{
		if(is_null($items)) {
			return $this;
		}

		return $this->render($items, $attributes);
	}

	public function render(array $items, $attributes=null)
	{
		if (!is_array($items)) {
			throw new \InvalidArgumentException(sprintf(
				'%s expects the $items argument to be one of "array"; received "%s"',
				__METHOD__,
				gettype($items)
			));
		}

		$class = empty($attributes['class']) ? $attributes['class'] : $this->rootClass;
		if (!preg_match('/(\s|^)'.$this->rootClass.'(\s|$)/', $class)) {
			$class .= ' ' . $this->rootClass;
		}
		$attributes['class'] = $class;

		$markup = $this->renderList($items);
		return sprintf('<div %s >%s</div>',
			$this->createAttributesString($attributes),
			$markup
		);

	}

	public function renderList($items) {

		$markup = '';
		foreach($items as $item) {
			$markup .= $this->renderItem($item);
		}

		return sprintf('<%s class="%s"> %s </%s>',
			$this->listNodeName,
			$this->listClass,
			$markup,
			$this->listNodeName
		);
	}

	public function renderItem(Item $item) {

		$itemId = $item->getId() ? $item->getId() : time();
		$itemType = $item->isExternalLink() ? 'Link Personalizado' : 'Página';
		$itemExternalUrl = $item->getExternalUrl();
		$itemPostId = $item->getPost() ? $item->getPost()->getId() : null;
		$itemPostTitle = $item->getPost() ? $item->getPost()->getTitle() : '';
		$itemLabel = $item->getLabel() ? $item->getLabel() : '';
		$itemLabelToView = $itemLabel ? $itemLabel : $itemPostTitle;

		$collapseRef = "collapse_edit_form_$itemId";

		$markup =  "<div class='item-controls'>
						<span class='item-type'>$itemType</span>
						<a role='button'
							data-toggle='collapse'
							href='#$collapseRef'
							aria-expanded='false'
							aria-controls=$collapseRef'>editar</a>
					</div>";

		$markup .= "<div class='".$this->handleClass."'>
						<span class='item-title'>$itemLabelToView</span>
					</div>";


		$markup .= '<div class="panel panel-default">
						<div id="'.$collapseRef.'" class="panel-collapse collapse">
							<div class="panel-body form-item-edit">
								<div class="form-group">
									<label for="">Rótulo de navegação</label>
									<input class="form-control" type="text" name="label" value="'.$itemLabelToView.'">
								</div>';
		if($item->isExternalLink()) {
			$markup.= '<div class="form-group">
							<label for="">Url</label>
							<input class="form-control" type="text" name="url" value="'.$itemExternalUrl.'">
						</div>';
		} else {
			$markup .= '<div class="form-group"><div class="help-block"><span>Original: '.$itemPostTitle.'</span></div></div>';
		}

		$markup .= '<div class="form-group actions">
						<a class="remove" href="#">Remover</a> | <a role="button" class="cancel" data-toggle="collapse"
							href="#'.$collapseRef.'"
							aria-expanded="false"
							aria-controls="'.$collapseRef.'">Cancelar</a>
					</div>
				</div>
			</div>
		</div>';

		if($item->hasChildren()) {
			$markup.= $this->renderList($item->getChildren());
		}

		$nodeAttributes = [
			'data-id' => $itemId,
			'data-label' => $itemLabel,
			'data-post' => $itemPostId,
			'data-external-url' => $itemExternalUrl,
			'data-type' => $item->isExternalLink() ? 'external' : 'post'
		];

		return sprintf('<%s class="%s" %s > %s </%s>',
			$this->itemNodeName,
			$this->itemClass,
			$this->createAttributesString($nodeAttributes),
			$markup,
			$this->itemNodeName
		);
	}

	/**
	 * @return string
	 */
	public function getListNodeName()
	{
		return $this->listNodeName;
	}

	/**
	 * @param string $listNodeName
	 */
	public function setListNodeName($listNodeName)
	{
		$this->listNodeName = $listNodeName;
	}

	/**
	 * @return string
	 */
	public function getItemNodeName()
	{
		return $this->itemNodeName;
	}

	/**
	 * @param string $itemNodeName
	 */
	public function setItemNodeName($itemNodeName)
	{
		$this->itemNodeName = $itemNodeName;
	}

	/**
	 * @return string
	 */
	public function getRootClass()
	{
		return $this->rootClass;
	}

	/**
	 * @param string $rootClass
	 */
	public function setRootClass($rootClass)
	{
		$this->rootClass = $rootClass;
	}

	/**
	 * @return string
	 */
	public function getListClass()
	{
		return $this->listClass;
	}

	/**
	 * @param string $listClass
	 */
	public function setListClass($listClass)
	{
		$this->listClass = $listClass;
	}

	/**
	 * @return string
	 */
	public function getItemClass()
	{
		return $this->itemClass;
	}

	/**
	 * @param string $itemClass
	 */
	public function setItemClass($itemClass)
	{
		$this->itemClass = $itemClass;
	}

	/**
	 * @return string
	 */
	public function getHandleClass()
	{
		return $this->handleClass;
	}

	/**
	 * @param string $handleClass
	 */
	public function setHandleClass($handleClass)
	{
		$this->handleClass = $handleClass;
	}

	/**
	 * @return string
	 */
	public function getCollapsedClass()
	{
		return $this->collapsedClass;
	}

	/**
	 * @param string $collapsedClass
	 */
	public function setCollapsedClass($collapsedClass)
	{
		$this->collapsedClass = $collapsedClass;
	}

}