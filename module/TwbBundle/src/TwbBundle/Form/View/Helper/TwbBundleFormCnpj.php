<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 08/02/2016
 * Time: 10:58
 */

namespace TwbBundle\Form\View\Helper;

use Util\View\Helper\Cnpj;
use Zend\Form\ElementInterface;
use Zend\Form\View\Helper\FormText;

class TwbBundleFormCnpj extends FormText
{
	private $cnpjFormatHelper;

	public function __construct($cpfFormatHelper=null)
	{
		if(!$cpfFormatHelper) {
			return $this->cnpjFormatHelper = $cpfFormatHelper;
		}
	}

	public function render(ElementInterface $element)
	{
		if($value = $element->getValue()) {
			$element->setValue($this->getCnpjFormatHelper()->render($value));
		}

		return parent::render($element);
	}

	/**
	 * Retrieve the Cpf formatter helper
	 *
	 * @return EscapeHtmlAttr
	 */
	protected function getCnpjFormatHelper()
	{
		if ($this->cnpjFormatHelper) {
			return $this->cnpjFormatHelper;
		}

		if (method_exists($this->view, 'plugin')) {
			$this->cnpjFormatHelper = $this->view->plugin('cnpj');
		}

		if (!$this->cnpjFormatHelper instanceof Cnpj) {
			$this->cnpjFormatHelper = new Cnpj();
		}

		return $this->cnpjFormatHelper;
	}

}