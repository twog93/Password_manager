<?php

namespace PasswordManager\Bundle\PlatformBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class PasswordRepository extends EntityRepository{
  
    public function getPasswordWithCategories(array $categoryNames)
  {
    $qb = $this->createQueryBuilder('p');

    // On fait une jointure avec l'entité Category avec pour alias « c »
    $qb
      ->join('p.categories', 'c')
      ->addSelect('c')
    ;

    // Puis on filtre sur le nom des catégories à l'aide d'un IN
    $qb->where($qb->expr()->in('c.name', $categoryNames));
    // La syntaxe du IN et d'autres expressions se trouve dans la documentation Doctrine

    // Enfin, on retourne le résultat
    return $qb
      ->getQuery()
      ->getResult()
    ;
  }
    public function getPasswordWithCategoriesByAuthor($user_id, array $categoryNames)
    {
        $qb = $this->createQueryBuilder('p');

        // On fait une jointure avec l'entité Category avec pour alias « c »
        $qb
            ->join('p.categories', 'c')
            ->addSelect('c')
        ;

        // Puis on filtre sur le nom des catégories à l'aide d'un IN
        $qb->where($qb->expr()->in('c.name', $categoryNames))->andWhere('p.user = :user_id')->setParameter('user_id', $user_id);
        // Enfin, on retourne le résultat
        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
    public function getPasswords($page, $nbPerPage)
  {
     $query = $this->createQueryBuilder('p')
      // Jointure sur l'attribut image
      ->leftJoin('p.image', 'i')
      ->addSelect('i')
      // Jointure sur l'attribut categories
      ->leftJoin('p.categories', 'c')
      ->addSelect('c')
      ->orderBy('p.date', 'DESC')
      ->getQuery();
      
    $query->setFirstResult(($page-1) * $nbPerPage)->setMaxResults($nbPerPage);

    return new Paginator($query, true);

  }
    public function myFindUserId($user_id)
    {

        $qb = $this->createQueryBuilder('p')
            ->where('p.user = :user')
            ->setParameter('user', $user_id)
        ;

        return $qb
            ->getQuery()
            ->getResult();
        
    }
    public function myFindGroup($groupNames)
    {


        $qb = $this->createQueryBuilder('p');
        $qb
            ->join('p.fos_group', 'g')
            ->addSelect('g');

        $qb->where($qb->expr()->in('g.name', $groupNames));

        return $qb
            ->getQuery()
            ->getResult();

    }
    public function getPasswordWithGroupByAuthor(array $groupNames)
    {
        $qb = $this->createQueryBuilder('p')
        ->join('p.groups', 'grp')
            ->addSelect('grp')
        ;


        $qb->where($qb->expr()->in('grp.name', $groupNames))
            ->andWhere('p.shared = :shared')
            ->setParameter('shared', true);
        // Enfin, on retourne le résultat
        return $qb
            ->getQuery()
            ->getResult()
            ;
    }
    public function getPasswordhared()
    {
        $qb = $this->createQueryBuilder('p')
        ->where('p.shared = :shared')
        ->setParameter('shared', true);

        return $qb
            ->getQuery()
            ->getResult()
            ;
    }






}
