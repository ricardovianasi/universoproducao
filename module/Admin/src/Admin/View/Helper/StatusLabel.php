<?php
namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Application\Entity\Post\PostStatus;

class StatusLabel extends AbstractHelper
{
	protected $mapStyle = [
		PostStatus::PUBLISHED => 'label label-success',
		PostStatus::DRAFT => 'label label-primary',
		PostStatus::PENDING_REVIEW => 'label label-warning',
		PostStatus::TRASH => 'label label-danger',
		'default' => 'label label-default'
	];

	protected $elementFormat = '<span class="%s label-sm">%s</span>';

	public function __invoke($status=null)
	{
		if($status) {
			return $this->render($status);
		}

		return $this;
	}

	public function render($status)
	{
		$style = array_key_exists($status, $this->mapStyle)
			? $this->mapStyle[$status]
			: $this->mapStyle['default'];

		return sprintf(
			$this->elementFormat,
			$style,
			PostStatus::get($status)
		);
	}

	public function getElementFormat()
	{
		return $this->elementFormat;
	}

	public function setElementFormat($format)
	{
		$this->elementFormat = $format;
		return $this;
	}

	public function getMapStyle()
	{
		return $this->mapStyle;
	}

	public function setMapStyle($mapStyle)
	{
		$this->mapStyle = $mapStyle;
		return $this;
	}
}