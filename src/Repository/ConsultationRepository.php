<?php

namespace App\Repository;

use App\Document\ConsultationAnimaux;
use Doctrine\ODM\MongoDB\DocumentManager;
use Doctrine\ODM\MongoDB\Repository\DocumentRepository;

class ConsultationRepository extends DocumentRepository
{

    public function __construct(DocumentManager $dm)
    {
        parent::__construct($dm, $dm->getUnitOfWork(), $dm->getClassMetadata(ConsultationAnimaux::class));
    }

/**
     * Compter le nombre de clics pour un animal spécifique.
     *
     * @param int $animalId
     * @return int
     */

    public function countByAnimalId(int $animalId): int
    {
        $count = $this->createQueryBuilder()
            ->field('animalId')->equals($animalId)
            ->count()
            ->getQuery()
            ->execute();

        return $count ?? 0;
    }
}