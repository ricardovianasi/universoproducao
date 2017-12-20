<?php
namespace Application\Repository\User;

use phpDocumentor\Reflection\Types\Parent_;
use Util\Repository\AbstractRepository;

class User extends AbstractRepository
{
    public function prepareSearch($criteria = [], $orderBy = [])
    {
        $search = parent::prepareSearch($criteria, $orderBy);
        $search->andWhere(self::QB_ALIAS.'.parent is NULL');

        return $search;
    }
}