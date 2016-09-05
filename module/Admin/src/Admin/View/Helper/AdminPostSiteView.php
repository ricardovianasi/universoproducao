<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 19/03/2016
 * Time: 10:22
 */

namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class AdminPostSiteView extends AbstractHelper
{
	public $labelHighlight = '<span class="label label-sm label-success"> Destaque </span>';
	public $containerFormat = '<div class="post-sites-items">%s</div>';
	public $tableFormat = '<table class="table table-hover table-light post-sites-table"><thead>
					<tr>
						<th width="80%%" class="uppercase"> Site </th>
						<th><a class="action remove-all" href="#">remover todos</a></th>
					</tr></thead>
					<tbody>%s</tbody></table>';

	public function __invoke($items=null)
	{
		if($items) {
			return sprintf($this->containerFormat, $this->render($items));
		}

		return $this;
	}

	public function render($items)
	{
		$markup = '';
		foreach ($items as $item) {
			$markup = $this->renderRow($item);
		}

		return sprintf($this->tableFormat, $markup);
	}

	public function renderRow($item)
	{
		$title = $item->getSite()->getName();
		if($item->isHighlight()) {
			$title.= $this->labelHighlight;
		}

		$markup = '<tr class="post-sites-item" data-id="'.$item->getSite()->getId().'">
						<td>'.$title.'</td>
						<td>
							<button class="btn btn-sm btn-default action remove"><i class="fa fa-close"></i></button>
							<input type="hidden" name="sites['.$item->getId().'][id]" value="'.$item->getSite()->getId().'" />
							<input type="hidden" name="sites['.$item->getId().'][highlight]" value="'.$item->getHighlight().'" />
						</td>
					</tr>';

		return $markup;
	}
}