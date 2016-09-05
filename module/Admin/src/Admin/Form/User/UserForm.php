<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 22/02/2016
 * Time: 12:07
 */

namespace Admin\Form\User;


use Admin\Form\Account\AccountInfoForm;
use Application\Entity\User\Profile;

class UserForm extends AccountInfoForm
{
	private $entityManager;

	public function __construct($em)
	{
		parent::__construct();
		$this->entityManager = $em;

		$this->add([
			'name' => 'is_administrator',
			'type' => 'checkbox',
			'options' => [
				'label' => 'Usuário Administrador',
			],
			'attributes' => [
				'class' => 'icheck',
			]
		]);

		$this->add([
			'name' => 'profile',
			'type' => 'select',
			'options' => [
				'label' => 'Perfil',
				'empty_option' => 'Selecione',
				'value_options' => $this->findProfiles()
			]
		]);
	}

	public function findProfiles()
	{
		$profiles = $this->getEntityManager()
			->getRepository(Profile::class)
			->findBy([], ['name'=>'ASC']);

		$array = [];
		foreach($profiles as $pr) {
			$array[$pr->getId()] = $pr->getName();
		}

		return $array;
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

	public function setData($data)
	{
		if(!empty($data['profile'])) {
			$profile = $data['profile'];
			if(is_object($profile)) {
				$data['profile'] = $profile->getId();
			}
		}

		if(!empty($data['is_administrator'])) {
			$isAdministrator = $data['is_administrator'];
			if($isAdministrator) {
				$this->get('is_administrator')->setAttribute('checked', 'checked');
			}
		}

		return parent::setData($data);
	}
}