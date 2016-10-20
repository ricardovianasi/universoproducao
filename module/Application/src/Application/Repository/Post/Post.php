<?php
namespace Application\Repository\Post;

use Application\Entity\Post\PostMeta;
use Application\Entity\Post\PostStatus;
use Application\Entity\Post\PostType;
use Util\Repository\AbstractRepository;

class Post extends AbstractRepository
{
	public function findByStr($str, $siteId, $language='pt')
	{
		$qb = $this->createQueryBuilder('p');
		$qb->select('p')
            ->andWhere('p.language = :lang')->setParameter('lang', $language)
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
            ->leftJoin('n.meta', 'm')
            ->andWhere('n.type = :type')
            ->andWhere('n.status = :status')
            ->andWhere('m.key = :key')
            ->andWhere('JSON_CONTAINS(m.value, :site) = 1')
            ->setParameters([
                'type' => PostType::NEWS,
                'status' => PostStatus::PUBLISHED,
                'key' => PostMeta::SITES,
                'site' => '{"'.$siteId.'":"false"}'
            ]);

        return $qb;
    }
}