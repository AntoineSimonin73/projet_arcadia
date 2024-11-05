<?php
namespace App\Repository;

use Doctrine\ODM\MongoDB\DocumentManager;
use App\Document\ConsultationAnimaux;

class ConsultationRepository
{
    private $dm;

    public function __construct(DocumentManager $dm)
    {
        $this->dm = $dm;
    }

    public function countViewsByAnimal(string $animalId): int
    {
        $consultation = $this->dm->getRepository(ConsultationAnimaux::class)->findOneBy(['animalId' => $animalId]);

        if (!$consultation) {
            $consultation = new ConsultationAnimaux();
            $consultation->setAnimalId($animalId);
            $consultation->setViews(1);
            $this->dm->persist($consultation);
        } else {
            $consultation->setViews($consultation->getViews() + 1);
        }

        $this->dm->flush();

        return $consultation->getViews();
    }
}