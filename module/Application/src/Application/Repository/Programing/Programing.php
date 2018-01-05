<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 05/12/2017
 * Time: 11:08
 */

namespace Application\Repository\Programing;


use Util\Repository\AbstractRepository;

class Programing extends AbstractRepository
{

    public function prepareSearch($criteria = [], $orderBy = [])
    {
        $queryBuilder = $this->createQueryBuilder(self::QB_ALIAS);

        $alias = self::QB_ALIAS;

        $queryBuilder->andWhere("$alias.parent is NULL");

        if(!empty($criteria['event'])) {
            $queryBuilder->andWhere(self::QB_ALIAS.'.event = :event')
                ->setParameter('event', $criteria['event']);
        }

        if(!empty($criteria['type'])) {
            if($criteria['type'] == 'movie') {
                $queryBuilder->andWhere("$alias.type = :typeMovie OR $alias.type = :typeSession")
                    ->setParameter('typeMovie', 'movie')
                    ->setParameter('typeSession', 'session');

            } else {
                $queryBuilder->andWhere(self::QB_ALIAS.'.type = :type')
                    ->setParameter('type', $criteria['type']);
            }
        }

        foreach($orderBy as $name=>$order) {
            $queryBuilder->addOrderBy(self::QB_ALIAS.".$name", $order);
        }

        return $queryBuilder;
    }
}

