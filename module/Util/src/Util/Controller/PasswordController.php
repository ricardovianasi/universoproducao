<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 02/02/2016
 * Time: 12:27
 */

namespace Util\Controller;

use Util\Security\Crypt;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\JsonModel;

class PasswordController extends AbstractActionController
{
	public function generatePasswordAction()
	{
		$jsonModel = new JsonModel();
		$jsonModel->setTerminal(true);

		$jsonModel->password = Crypt::makePassword();

		return $jsonModel;
	}
}