<?php
namespace Application\Repository\Site;

use Util\Repository\AbstractRepository;

class Site extends AbstractRepository
{
	public function findEnabledSites()
	{
		return $this->findBy(['status' => true], ['name' => 'ASC']);
	}

}