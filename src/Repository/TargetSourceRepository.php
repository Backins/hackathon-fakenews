<?php

namespace App\Repository;

use App\Entity\TargetSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method TargetSource|null find($id, $lockMode = null, $lockVersion = null)
 * @method TargetSource|null findOneBy(array $criteria, array $orderBy = null)
 * @method TargetSource[]    findAll()
 * @method TargetSource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TargetSourceRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, TargetSource::class);
    }

}
