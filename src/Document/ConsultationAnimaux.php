<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class ConsultationAnimaux
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\Field(type: 'int')]
    private $animalId;

    #[MongoDB\Field(type: 'date')]
    private $clickedAt;

    public function __construct(int $animalId)
    {
        $this->animalId = $animalId;
        $this->clickedAt = new \DateTime();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getAnimalId(): ?int
    {
        return $this->animalId;
    }

    public function getClickedAt(): ?\DateTime
    {
        return $this->clickedAt;
    }

    public function setClickedAt(\DateTime $clickedAt): self
    {
        $this->clickedAt = $clickedAt;

        return $this;
    }
}