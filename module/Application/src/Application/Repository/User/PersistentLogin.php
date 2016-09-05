<?php
namespace Application\Repository\User;

use Util\Security\Crypt;
use Util\Repository\AbstractRepository;

class PersistentLogin extends AbstractRepository {

	public function create($identity) {

		//Primeiro limpa todos os registros de logins persistentes
		$this->removeAllByUser($identity->getId());

		//Cria um novo
		$entity = new \Application\Entity\User\PersistentLogin(array(
			'serialIdentifier' => Crypt::generateRandomToken(),
			'token' => Crypt::generateRandomToken(),
			'user' => $identity
		));

		$this->getEntityManager()->persist($entity);
		$this->getEntityManager()->flush();
		return $entity;
	}

	public function update(\Application\Entity\User\PersistentLogin $cookieEntity) {
		$cookieEntity->setToken(Crypt::generateRandomToken());
		$this->update($cookieEntity);
		return $cookieEntity;
	}

	public function removeAllByUser($idUser) {
		$qb = $this->createQueryBuilder('p');
		$qb->delete('Application\Entity\User\PersistentLogin', 'p')
			->where('p.user = :idUser')
			->setParameter('idUser', $idUser);

		$qb->getQuery()->execute();
		$this->getEntityManager()->flush();
	}

	/**
	 *
	 * @param int $idUser
	 * @return \Application\Entity\PersistentLogin
	 */
	public function findByUser($idUser) {
		return $this->findOneBy(array('user'=>$idUser));
	}

	/**
	 *
	 * @param id $idUser
	 * @param string $token
	 * @param string $serialIdentifier
	 * @return Ambigous <Null, \Application\Entity\PersistentLogin>
	 */
	public function validate($idUser, $token, $serialIdentifier) {

		$qb = $this->createQueryBuilder('p');
		$qb->andWhere('p.user = :idUser')
			->andWhere('p.token = :token')
			->andWhere('p.serialIdentifier = :identifier');

		$qb->setParameters(array(
			'idUser' => $idUser,
			'token' => $token,
			'identifier' => $serialIdentifier
		));

		return $qb->getQuery()->getOneOrNullResult();
	}

}