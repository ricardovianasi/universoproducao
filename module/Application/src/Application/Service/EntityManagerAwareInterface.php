<?php
namespace Application\Service;

interface EntityManagerAwareInterface
{
    public function setEntityManager($em);
    public function getEntityManager();
}