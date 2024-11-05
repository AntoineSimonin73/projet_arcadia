<?php

namespace App\Repository;

use App\Entity\Avis;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Avis>
 */
class AvisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Avis::class);
    }

     /**
     * @return Avis[] Returns an array of Avis objects
     */
    public function findLastTwoVisible(): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.isVisible = :visible')
            ->setParameter('visible', true)
            ->orderBy('a.id', 'DESC')
            ->setMaxResults(2)
            ->getQuery()
            ->getResult();
    }
}
