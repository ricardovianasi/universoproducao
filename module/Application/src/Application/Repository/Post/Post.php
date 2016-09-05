<?php
namespace Application\Repository\Post;

use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Util\Repository\AbstractRepository;

class Post extends AbstractRepository
{
	public function findByStr($str, $siteId)
	{
		$qb = $this->createQueryBuilder('p');
		$qb->select('p')
			->andWhere('p.title like :title')->setParameter('title', "%$str%")
			->andWhere('p.site = :siteId')->setParameter('siteId', $siteId)
			->andWhere('p.status = :status')->setParameter('status', PostStatus::PUBLISHED)
			->andWhere('p.type = :postType')->setParameter('postType', PostType::PAGE)
			->orderBy('p.postDate', 'DESC');

		$qb->getQuery()->getSQL();
		$qb->getParameters();

		return $qb->getQuery()->getResult();
	}
}