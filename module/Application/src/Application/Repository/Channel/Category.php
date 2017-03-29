<?php
namespace Application\Repository\Channel;

use Application\Entity\Channel\Video;
use Util\Repository\AbstractRepository;

class Category extends AbstractRepository
{
    public function findChannelCategories()
    {
        $qb = $this->createQueryBuilder('c');
        $qb->select('c')
            ->innerJoin('c.videos', 'v')
            ->andWhere('c.isVisible = 1')
            ->orderBy('v.date', 'DESC')
            ->groupBy('c.id');

        return $qb->getQuery()->getResult();
    }
}