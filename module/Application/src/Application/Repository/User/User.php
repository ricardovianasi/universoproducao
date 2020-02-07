<?php
namespace Application\Repository\User;

use phpDocumentor\Reflection\Types\Parent_;
use Util\Repository\AbstractRepository;

class User extends AbstractRepository
{
    public function prepareSearch($criteria = [], $orderBy = [])
    {
        $qb = $this->createQueryBuilder('p');

        if(!empty($criteria['identifier'])) {
            $qb
                ->andWhere('p.identifier like :identifier')
                ->setParameter('identifier', "%".$criteria['identifier']."%");
        }

        if(!empty($criteria['name'])) {
            $qb
                ->andWhere('p.name like :name')
                ->setParameter('name', "%".$criteria['name']."%");
        }

        if(!empty($criteria['email'])) {
            $qb
                ->andWhere('p.email like :email')
                ->setParameter('email', "%".$criteria['email']."%");
        }

        if(!empty($criteria['origin'])) {
            $qb
                ->andWhere('p.origin = :origin')
                ->setParameter('origin', $criteria['origin']);
        }

        if(!empty($criteria['category'])) {
            $qb
                ->andWhere('p.category = :category')
                ->setParameter('category', $criteria['category']);
        }

        if(!empty($criteria['subcategory'])) {
            $qb
                ->andWhere('p.subcategory = :subcategory')
                ->setParameter('subcategory', $criteria['subcategory']);
        }

        if(!empty($criteria['status'])) {
            $qb
                ->andWhere('p.status = :status')
                ->setParameter('status', $criteria['status']);
        }

        if(!empty($criteria['tag'])) {
            $qb
                ->andWhere('p.tag = :tag')
                ->setParameter('tag', $criteria['tag']);
        }

        if(!empty($criteria['state'])) {
            $qb
                ->innerJoin('p.city', 'c')
                ->andWhere('c.state = :idState')
                ->setParameter('idState', $criteria['state']);
        }

        if(!empty($criteria['city'])) {
            $qb
                ->andWhere('p.city = :city')
                ->setParameter('city', $criteria['city']);
        }

        if(!empty($criteria['variable_field'])) {
            $qb
                ->andWhere('p.variable_field like %:variable_field%')
                ->setParameter('variable_field', "%".$criteria['variable_field']."%");
        }

        $qb->andWhere('p.parent is NULL');

        foreach($orderBy as $name=>$order) {
            $qb->addOrderBy("p.$name", $order);
        }


        return $qb;
    }
}