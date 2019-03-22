<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method ReviewRepository|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReviewRepository|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReviewRepository[]    findAll()
 * @method ReviewRepository[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Review::class);
    }

    // /**
    //  * @return Review[] Returns an array of Review objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */


    public function getCount($value): array
    {
        return $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.urlArticle = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    public function checkVoteUser($link, $user): bool
    {
        $result = $this->createQueryBuilder('r')
            ->select('COUNT(r.id)')
            ->andWhere('r.urlArticle = :link')
            ->andWhere('r.userReview = :user')
            ->setParameter('link', $link)
            ->setParameter('user', $user)
            ->getQuery()
            ->getOneOrNullResult()
        ;
        if($result[1]){
            return true;
        }
        return false;
    }
}
