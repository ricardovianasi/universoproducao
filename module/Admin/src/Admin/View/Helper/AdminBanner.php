<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 22/03/2016
 * Time: 12:00
 */

namespace Admin\View\Helper;

use Application\Entity\Banner\Banner;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class AdminBanner extends AbstractHelper implements ServiceLocatorAwareInterface
{
	private $serviceLocator;

	protected $tableFormat =
		'<table class="table table-bordered table-hover gallery-items">
			<thead>
				<tr role="row" class="heading">
					<th width="8%%"> Image </th>
					<th width="20%%"> Título </th>
					<th width="20%%"> Link </th>
					<th width="20%%"> Créditos </th>
					<th width="10%%"> Período </th>
					<th width="8%%"> Nova Página </th>
					<th width="8%%"> </th>
				</tr>
			</thead>
			<tbody>%s</tbody>
		</table>';

	protected $rowFormat = '<tr class="gallery-item">%s</tr>';

	protected $columnFormat = '<td>%s</td>';

	protected $columnAction = '<div class="margin-bottom-5" data-date-format="dd/mm/yyyy">
									<button type="button" class="btn btn-default gallery-item-action-remove">Remover</button>
								</div>
								<a class="btn btn-default gallery-item-move"><i class="fa fa-arrows-v"></i> Mover</a>';

	public function __invoke($items=null)
	{
		if(!is_null($items)) {
			return $this->render($items);
		}

		return $this;
	}

	public function render($items=[])
	{
		$markup = '';
		foreach ($items as $item) {
			$markup .= $this->renderRow($item);
		}

		return sprintf($this->tableFormat, $markup);
	}

	public function renderRow(Banner $item)
	{
		$markup = '';

		$data = [
			$this->renderthumbColumn($item->getId(), $item->getFile()),
			$this->renderTitleColumn($item->getId(), $item->getTitle()),
			$this->renderLinkColumn($item->getId(), $item->getLink()),
			$this->renderCreditsColumn($item->getId(), $item->getCredits()),
			$this->renderDateColumn($item->getId(), $item->getStartDate(), $item->getEndDate()),
			$this->renderTargetBlackColumn($item->getId(), $item->getTargetBlank()),
			$this->columnAction
		];

		foreach ($data as $d) {
			$markup .= sprintf($this->columnFormat, $d);
		}

		return sprintf($this->rowFormat, $markup);
	}

	protected function renderthumbColumn($id, $value="")
	{
		return '<input name="banner['.$id.'][file]" class="form-control" type="hidden" value="'.$value.'">
			<a href="'.$value.'" class="fancybox-button" data-rel="fancybox-button">
			<img class="img-responsive" src="'.str_replace('source', 'thumbs', $value).'" alt=""></a>';
	}
	
	protected function renderTitleColumn($id, $value="")
	{
		return '<textarea name="banner['.$id.'][title]" rows="2" placeholder="Informe um texto" class="form-control">'.
			$value.'</textarea>';
	}

	protected function renderLinkColumn($id, $value="")
	{
		return '<input name="banner['.$id.'][link]" placeholder="http://" class="form-control" type="text" 
			value="'.$value.'"';
	}

	protected function renderCreditsColumn($id, $value="")
	{
		return '<input name="banner['.$id.'][credits]" class="form-control" type="text" value="'.$value.'">';
	}

	protected function renderDateColumn($id, $startDate="", $endDate="")
	{
		if($startDate instanceof \DateTime) {
			$startDate = $startDate->format('d/m/Y');
		}

		if($endDate instanceof \DateTime) {
			$endDate = $endDate->format('d/m/Y');
		}

		return '<div class="input-group date date-picker margin-bottom-5" data-date-format="dd/mm/yyyy">
				<input type="text" name="banner['.$id.'][start_date]" class="form-control" value="'.$startDate.'">
				<span class="input-group-btn">
					<button class="btn default" type="button">
						<i class="fa fa-calendar"></i>
					</button>
				</span>
			</div>
			<div class="input-group date date-picker" data-date-format="dd/mm/yyyy">
				<input type="text" name="banner['.$id.'][end_date]" class="form-control" value="'.$endDate.'">
				<span class="input-group-btn">
					<button class="btn default" type="button">
						<i class="fa fa-calendar"></i>
					</button>
				</span>
			</div>';
	}

	protected function renderTargetBlackColumn($id, $value=null)
	{
		$checked = (bool) $value ? 'checked="checked"' : '';
		return '<input type="hidden" name="banner['.$id.'][target_blank]" value="0">
			<input type="checkbox" name="banner['.$id.'][target_blank]" '.$checked.' class="icheck" value="1">';
	}

	/**
	 * @return string
	 */
	public function getTableFormat()
	{
		return $this->tableFormat;
	}

	/**
	 * @param string $tableFormat
	 */
	public function setTableFormat($tableFormat)
	{
		$this->tableFormat = $tableFormat;
	}

	/**
	 * @return string
	 */
	public function getRowFormat()
	{
		return $this->rowFormat;
	}

	/**
	 * @param string $rowFormat
	 */
	public function setRowFormat($rowFormat)
	{
		$this->rowFormat = $rowFormat;
	}

	/**
	 * @return string
	 */
	public function getColumnFormat()
	{
		return $this->columnFormat;
	}

	/**
	 * @param string $columnFormat
	 */
	public function setColumnFormat($columnFormat)
	{
		$this->columnFormat = $columnFormat;
	}

	/**
	 * @return string
	 */
	public function getColumnAction()
	{
		return $this->columnAction;
	}

	/**
	 * @param string $columnAction
	 */
	public function setColumnAction($columnAction)
	{
		$this->columnAction = $columnAction;
	}

	public function setServiceLocator(ServiceLocatorInterface $serviceLocator) {
		$this->serviceLocator = $serviceLocator;
		return $this;
	}

	public function getServiceLocator() {
		return $this->serviceLocator;
	}

	public function getServiceManager()
	{
		if($this->serviceLocator) {
			return $this->serviceLocator->getServiceLocator();
		}

		return null;
	}
}