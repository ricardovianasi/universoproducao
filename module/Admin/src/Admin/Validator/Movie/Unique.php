<?php
/**
 * Created by PhpStorm.
 * User: Ricardo Viana
 * Date: 02/10/2017
 * Time: 15:16
 */

namespace Admin\Validator\Movie;

use Doctrine\ORM\QueryBuilder;
use Zend\Validator\AbstractValidator;
use Zend\Validator\Exception;

class Unique extends AbstractValidator
{
    /**
     * Error constants
     */
    const ERROR_OBJECT_NOT_UNIQUE = 'objectNotUnique';

    /**
     * @var array Message templates
     */
    protected $messageTemplates = array(
        self::ERROR_OBJECT_NOT_UNIQUE => "There is already another object matching '%value%'",
    );

    /**
     * @var ObjectManager
     */
    protected $objectManager;

    protected $scapeId;

    protected $userContext;

    public function __construct(array $options)
    {
        if(isset($options['object_manager'])) {
            $this->objectManager = $options['object_manager'];
        }

        if(isset($options['scape_id'])) {
            $this->scapeId = $options['scape_id'];
        }

        if(isset($options['user_context'])) {
            $this->userContext = $options['user_context'];
        }

        parent::__construct($options);
    }

    public function isValid($value)
    {
        /** @var QueryBuilder $qb */
        $qb = $this->objectManager->createQueryBuilder('m');

        $qb->andWhere('m.title = :title')->setParameter('title', $value);
        $qb->select('count(m.id)');
        if($this->scapeId) {
            $qb->andWhere('m.id != :scapeId')
                ->setParameter('scapeId', $this->scapeId);
        }

        if($this->userContext) {
            $qb->andWhere('m.author = :userContext')
                ->setParameter('userContext', $this->userContext);
        }

        $result = $qb->getQuery()->getSingleScalarResult();
        if($result) {
            $this->error(self::ERROR_OBJECT_NOT_UNIQUE, $value);
            return false;
        }

        return true;
    }
}