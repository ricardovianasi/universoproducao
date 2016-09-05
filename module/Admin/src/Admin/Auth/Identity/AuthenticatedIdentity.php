<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 29/12/2015
 * Time: 10:23
 */

namespace Admin\Auth\Identity;

use Zend\Permissions\Rbac\AbstractRole as AbstractRbacRole;

class AuthenticatedIdentity extends AbstractRbacRole implements IdentityInterface
{
	protected $identity;

	public function __construct($identity)
	{
		$this->identity = $identity;
	}

	public function getRoleId()
	{
		return $this->name;
	}

	public function getAuthenticationIdentity()
	{
		return $this->identity;
	}

	public function setName($name)
	{
		$this->name = $name;
	}
}