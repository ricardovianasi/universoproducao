<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 09/12/2015
 * Time: 10:00
 */

namespace Admin\Controller\Plugin;

use Application\Entity\User\Log;
use Application\Entity\User\User;
use Doctrine\ORM\EntityManager;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class UserLog extends AbstractPlugin
{
	/**
	 * Doctrine EntityManager object
	 *
	 * @var EntityManager
	 */
	private $entityManager;


	public function log($user, $msg)
    {
        if(empty($msg))
            return;

        if(is_numeric($user)) {
            $user = $this->getEntityManager()->getRepository(User::class)->find($user);
        }

        if(!$user)
            return;

        $log = new Log();
        $log->setUser($user);
        $log->setLog($msg);

        $this->getEntityManager()->persist($log);
        $this->getEntityManager()->flush();
    }

	/**
	 * @return EntityManager
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