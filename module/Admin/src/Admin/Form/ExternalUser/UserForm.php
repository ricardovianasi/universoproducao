<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 07/01/2016
 * Time: 15:09
 */

namespace Admin\Form\ExternalUser;

use Application\Entity\City;
use Application\Entity\State;
use Zend\Form\Element\Checkbox;
use Zend\Form\Form;
use Zend\Validator\EmailAddress;
use Zend\Validator\NotEmpty;
use Zend\InputFilter\Factory;

class UserForm extends Form
{
	private $entityManager;

	public function __construct($em)
	{
		parent::__construct('user-form');
		$this->setAttributes([
			'method' => 'POST',
			'class' => 'user-form default-form-actions enable-validators',
			'id' => 'post-form'
		]);

		$this->entityManager = $em;

		$this->add([
			'name' => 'cpf',
			'type' => 'TwbBundle\Form\Element\Cpf',
			'options' => [
				'label' => 'CPF',
			],
			'attributes' => [
				'required' => 'required'
			],
		]);

		$this->add([
			'name' => 'first_name',
			'required' => true,
			'options' => [
				'label' => 'Primeiro nome',
			],
			'attributes' => [
				'required' => 'required'
			],

		]);

		$this->add([
			'name' => 'last_name',
			'type' => 'text',
			'required' => true,
			'type' => 'text',
			'options' => [
				'label' => 'Sobrenome',
			],
			'attributes' => [
				'required' => 'required'
			],
		]);

		$this->add([
			'name' => 'email',
			'required' => true,
			'type' => 'text',
			'options' => [
				'label' => 'Email',
			],
			'attributes' => [
				'required' => 'required'
			],
			'validators'=>array(
				new EmailAddress()
			),
		]);

		$this->add([
			'name' => 'fixed_phone',
			'required' => false,
			'type' => 'text',
			'options' => [
				'label' => 'Telefone Fixo',
			]
		]);

		$this->add([
			'name' => 'mobile_phone',
			'required' => false,
			'type' => 'text',
			'options' => [
				'label' => 'Celular',
			]
		]);

		$this->add([
			'name' => 'site',
			'required' => false,
			'type' => 'text',
			'options' => [
				'label' => 'Site',
			]
		]);

		$this->add([
			'name' => 'birth_date',
			'required' => true,
			'type' => 'TwbBundle\Form\Element\DatePicker',
			'options' => [
				'label' => 'Data de Nascimento',
			],
			'attributes' => [
				'required' => 'required'
			]
		]);

		$this->add([
			'name' => 'cep',
			'required' => true,
			'type' => 'text',
			'options' => [
				'label' => 'CEP',
			],
			'attributes' => [
				'required' => 'required'
			]
		]);

		$this->add([
			'name' => 'address',
			'required' => true,
			'type' => 'text',
			'options' => [
				'label' => 'Rua',
			],
			'attributes' => [
				'required' => 'required'
			]
		]);

		$this->add([
			'name' => 'number',
			'required' => true,
			'type' => 'text',
			'options' => [
				'label' => 'Número',
			],
			'attributes' => [
				'required' => 'required'
			]
		]);

		$this->add([
			'name' => 'district',
			'required' => true,
			'type' => 'text',
			'options' => [
				'label' => 'Bairro',
			],
			'attributes' => [
				'required' => 'required'
			]
		]);

		$this->add([
			'name' => 'complement',
			'required' => false,
			'type' => 'text',
			'options' => [
				'label' => 'Complemento',
			]
		]);

		$this->add([
			'name' => 'state',
			'required' => true,
			'type' => 'select',
			'options' => [
				'label' => 'Estado',
				'empty_option' => 'Selecione',
				'value_options' => $this->findStates()
			],
			'attributes' => [
				'required' => 'required',
				'id' => 'state'
			]
		]);

		$this->add([
			'name' => 'city',
			'required' => true,
			'type' => 'select',
			'options' => [
				'label' => 'Cidade',
				'empty_option' => 'Selecione'
			],
			'attributes' => [
				'required' => 'required',
				'id' => 'city'
			]
		]);

		$this->add([
			'name' => 'facebook',
			'required' => false,
			'type' => 'text',
			'options' => [
				'label' => 'Facebook',
			],
		]);

		$this->add([
			'name' => 'twitter',
			'required' => false,
			'type' => 'text',
			'options' => [
				'label' => 'Twitter',
			],
		]);

		$confirmedRegister = new Checkbox('confirmed_register');
		$confirmedRegister->setLabel('Confirmar o cadastro do usuário')
			->setAttribute('class', 'icheck')
			->setOption('disable-twb', true);
		$this->add($confirmedRegister);
	}

	protected function findStates()
	{
		$estados = $this->getEntityManager()
			->getRepository(State::class)
			->findBy([], ['name'=>'ASC']);

		$array = [];
		foreach($estados as $es) {
			$array[$es->getId()] = $es->getName();
		}

		return $array;
	}

	public function setData($data)
	{
		if(!empty($data['city'])) {

			if(is_object($data['city'])) {
				$city = $data['city'];
			} elseif(is_scalar($data['city'])) {
				$city = $this->getEntityManager()->getRepository(City::class)->find($data['city']);
			}

			$data['city'] = $city->getId();

			$stateId = $city->getState()->getId();
			if(empty($data['state'])) {
				$data['state'] = $stateId;
			}

			$cities = $this->getEntityManager()
				->getRepository(City::class)
				->findBy(['state'=>$stateId], ['name'=>'ASC']);

			$citiesArray = [];
			foreach($cities as $c) {
				$citiesArray[$c->getId()] = $c->getName();
			}
			$this->get('city')->setValueOptions($citiesArray);
		}

		return parent::setData($data); // TODO: Change the autogenerated stub
	}

	/**
	 * @return \Doctrine\Orm\EntityManager
	 */
	public function getEntityManager()
	{
		return $this->entityManager;
	}

	public function setEntityManager($em)
	{
		$this->entityManager = $em;
		return $this;
	}
}