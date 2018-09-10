<?php

namespace PasswordManager\Bundle\PlatformBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;

class ApplicationRepository extends EntityRepository{


  public function getApplicationsWithPassword($limit){

   $qb = $this->createQueryBuilder('a');

   $qb->join('a.password', 'adv')->addSelect('adv');

   $qb->setMaxResults($limit);

    return $qb->getQuery()->getResult();

  }






}