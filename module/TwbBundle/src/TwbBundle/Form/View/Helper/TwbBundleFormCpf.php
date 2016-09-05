<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/02/2016
 * Time: 10:58
 */

namespace TwbBundle\Form\View\Helper;

use Util\View\Helper\Cpf;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormText;

class TwbBundleFormCpf extends FormText
{
	private $cpfFormatHelper;

	public function __construct($cpfFormatHelper=null)
	{
		if(!$cpfFormatHelper) {
			return $this->cpfFormatHelper = $cpfFormatHelper;
		}
	}

	public function render(ElementInterface $element)
	{
		if($value = $element->getValue()) {
			$element->setValue($this->getCpfFormatHelper()->render($value));
		}

		return parent::render($element);
	}

	/**
	 * Retrieve the Cpf formatter helper
	 *
	 * @return EscapeHtmlAttr
	 */
	protected function getCpfFormatHelper()
	{
		if ($this->cpfFormatHelper) {
			return $this->cpfFormatHelper;
		}

		if (method_exists($this->view, 'plugin')) {
			$this->cpfFormatHelper = $this->view->plugin('cpf');
		}

		if (!$this->cpfFormatHelper instanceof Cpf) {
			$this->cpfFormatHelper = new Cpf();
		}

		return $this->cpfFormatHelper;
	}

}