<?php

namespace PasswordManager\Bundle\PlatformBundle\Entity;


use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;


class CategoryRepository extends EntityRepository 
{
    public function getLikeQueryBuilder($pattern){

        return $this
		->createQueryBuilder('c')
		->where('c.name LIKE :pattern')
		->setParameter('pattern', $pattern);
    }
}

