<?php
namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Thumbor extends AbstractHelper
{

	private $thumborServer;

	private $thumbor;

	public function __construct($thumborServer)
	{
		$this->thumborServer = $thumborServer;
	}

	public function __invoke()
	{
		if(!$this->thumbor) {
			$this->thumbor = \Thumbor\Url\BuilderFactory::construct($this->thumborServer);
		}

		return $this->thumbor;
	}

}