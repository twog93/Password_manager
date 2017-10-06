<?php

namespace PasswordManager\Bundle\PlatformBundle\Entity;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;

class AdvertRepository extends EntityRepository{
  
  public function getAdvertWithCategories(array $categoryNames)
  {
    $qb = $this->createQueryBuilder('a');

    // On fait une jointure avec l'entité Category avec pour alias « c »
    $qb
      ->join('a.categories', 'c')
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
  
   public function getAdverts($page, $nbPerPage)
  {
     $query = $this->createQueryBuilder('a')
      // Jointure sur l'attribut image
      ->leftJoin('a.image', 'i')
      ->addSelect('i')
      // Jointure sur l'attribut categories
      ->leftJoin('a.categories', 'c')
      ->addSelect('c')
      ->orderBy('a.date', 'DESC')
      ->getQuery();
      
    $query->setFirstResult(($page-1) * $nbPerPage)->setMaxResults($nbPerPage);

    return new Paginator($query, true);

  }
    public function myFindUserId($user_id)
    {

        $qb = $this->createQueryBuilder('a')
            ->where('a.user = :user')
            ->setParameter('user', $user_id)
        ;

        return $qb
            ->getQuery()
            ->getResult();

        // On n'ajoute pas de critère ou tri particulier, la construction
        // de notre requête est finie

        // On récupère la Query à partir du QueryBuilder
        $query = $queryBuilder->getQuery();

        // On récupère les résultats à partir de la Query
        $results = $query->getResult();

        // On retourne ces résultats
        return $results;
    }
}