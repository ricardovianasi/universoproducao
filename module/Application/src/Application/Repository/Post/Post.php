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

	public function findNewsQb($siteId)
    {
        $qb = $this->createQueryBuilder('n');
        $qb->select('n')
            ->andWhere('n.type = :type')
            ->andWhere('n.status = :status')
            ->leftJoin('n.sites', 's')
            ->andWhere("(n.publishAllSites = :allSite OR s.site = :siteId)")
            ->setParameters([
                'type' => PostType::NEWS,
                'status' => PostStatus::PUBLISHED,
                'allSite' => true,
                'siteId' => $siteId
            ]);

        return $qb;
    }
}