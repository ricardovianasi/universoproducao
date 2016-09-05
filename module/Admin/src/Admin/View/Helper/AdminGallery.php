<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 22/03/2016
 * Time: 12:00
 */

namespace Admin\View\Helper;

use Application\Entity\Banner\Banner;
use Application\Entity\Gallery\Gallery;
use Zend\Form\Form;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\AbstractHelper;

class AdminGallery extends AbstractHelper implements ServiceLocatorAwareInterface
{
	private $serviceLocator;

	protected $tableFormat =
		'<table class="table table-bordered table-hover gallery-items">
			<thead>
				<tr role="row" class="heading">
					<th width="8%%"> Imagem </th>
					<th width="40%%"> Descrição </th>
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

	public function renderRow(Gallery $item)
	{
		$markup = '';

		$data = [
			$this->renderthumbColumn($item->getId(), $item->getFile()),
			$this->renderTextColumn($item->getId(), $item->getDescription()),
			$this->columnAction
		];

		foreach ($data as $d) {
			$markup .= sprintf($this->columnFormat, $d);
		}

		return sprintf($this->rowFormat, $markup);
	}

	protected function renderthumbColumn($id, $value="")
	{
		return '<input name="gallery['.$id.'][file]" class="form-control" type="hidden" value="'.$value.'">
			<a href="'.$value.'" class="fancybox-button" data-rel="fancybox-button">
			<img class="img-responsive" src="'.str_replace('source', 'thumbs', $value).'" alt=""></a>';
	}
	
	protected function renderTextColumn($id, $value="")
	{
		return '<textarea name="gallery['.$id.'][description]" rows="2" placeholder="Descrição da Imagem" class="form-control">'.
			$value.'</textarea>';
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