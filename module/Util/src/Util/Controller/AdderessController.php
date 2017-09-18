<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 31/01/2016
 * Time: 20:40
 */

namespace Util\Controller;

use Application\Entity\State;
use Application\Entity\City;
use Application\Service\EntityManagerAwareInterface;
use Zend\Http\Request;
use Zend\View\Model\JsonModel;

class AdderessController extends AbstractController implements EntityManagerAwareInterface
{
	public static $postmon = 'http://api.postmon.com.br/v1/cep/';

	public function cepAction()
	{
		$jsonModel = new JsonModel();
		$jsonModel->setTerminal(true);

		try {
			$cep = $this->params()->fromPost('cep');
			if(!$cep) {
				$jsonModel->error = 'Cep nao informado!';
			}

			$data = $this->callPostmonApi($cep);
			if($data) {
				$address = [];
				$state = $this->getEntityManager()
					->getRepository(State::class)
					->findOneBy(['acronyme'=>$data['estado']]);
				if($state) {
					$address['state'] = $state->getId();

					$cities = $this->getEntityManager()
						->getRepository(City::class)
						->findBy(['state' => $state->getId()], ['name'=>'ASC']);
					$citiesArray[] = '<option>Selecione</option>';
					foreach($cities as $c) {
						$citiesArray[] = '<option value="'.$c->getId().'">'.$c->getName().'</option>';
					}
					$address['cities'] = $citiesArray;

					$city = $this->getEntityManager()
						->getRepository(City::class)
						->findOneBy([
							'name'=>$data['cidade'],
							'state' => $state->getId()
						]);
					if($city) {
						$address['city'] = $city->getId();

					}
				}
				$address['address'] = $data['logradouro'];
				$address['district'] = $data['bairro'];
				$jsonModel->cep = $address;
			} else {
				$jsonModel->error = 'Cep não encontrado!';
			}
		} catch(\Exception $e) {
			$jsonModel->error = 'Não foi possível localizar o cep informado. Por favor, tente novamente ou informe o endereço manualmente.';
		}

		return $jsonModel;
	}

	public function citiesAction()
	{
		$jsonModel = new JsonModel();
		$jsonModel->setTerminal(true);

		try {

			$state = $this->params()->fromPost('state');
			if (!$state) {
				$jsonModel->error = "Estado nao informado!";
			}

			$cities = $this->getRepository(City::class)->findBy(['state'=>$state], ['name'=>'ASC']);
			$citiesArray[] = '<option>Selecione</option>';
			foreach($cities as $c) {
				$citiesArray[] = '<option value="'.$c->getId().'">'.$c->getName().'</option>';
			}

			$jsonModel->cities = $citiesArray;

		} catch(\Exception $e) {
			$jsonModel->error = 'Não foi possível localizar as cidades. Por favor, tente novamente.';
		}

		return $jsonModel;
	}

	protected function callPostmonApi($cep)
	{
		$request = new Request();
		$request->getHeaders()->addHeaders([
			'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8'
		]);
		$request->setUri(self::$postmon . $cep);
		$request->setMethod('GET');

		$client = new \Zend\Http\Client();
		$response = $client->dispatch($request);

		return json_decode($response->getBody(), true);
	}


}
