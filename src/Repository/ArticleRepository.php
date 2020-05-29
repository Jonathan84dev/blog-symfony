<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Article::class);
    }
    

    // /**
    //  * @return Article[] Returns an array of Article objects
    //  */
   
    public function contient($value)
    {
        return $this->createQueryBuilder('a')
            ->orWhere('a.title LIKE :val')
            ->orWhere('a.content LIKE :val')
            ->orWhere('a.short_content LIKE :val')
            ->setParameter('val', "%".$value."%")
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(3)
            ->getQuery()
            ->getResult()
        ;
    }

    // /**
    // * @return Article[] Retourne les X derniers articles publiés
    //*/

    public function findLastArticles($number) {
        return $this->createQueryBuilder('a')
        ->orderBy('a.created_at', 'DESC')
        ->setMaxResults($number)
        ->getQuery()
        ->getResult();
    }


    /*
    public function findOneBySomeField($value): ?Article
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
