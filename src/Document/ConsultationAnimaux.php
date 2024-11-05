<?php
namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class ConsultationAnimaux
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\Field(type: "string")]
    private $animalId;

    #[MongoDB\Field(type: "date")]
    private $consultedAt;

    #[MongoDB\Field(type: "int")]
    private $views = 0;

    public function getId()
    {
        return $this->id;
    }

    public function getAnimalId()
    {
        return $this->animalId;
    }

    public function setAnimalId(string $animalId): self
    {
        $this->animalId = $animalId;

        return $this;
    }

    public function getConsultedAt(): ?\DateTime
    {
        return $this->consultedAt;
    }

    public function setConsultedAt(\DateTime $consultedAt): self
    {
        $this->consultedAt = $consultedAt;

        return $this;
    }

    public function getViews(): int
    {
        return $this->views;
    }

    public function incrementViews(): void
    {
        $this->views++;
    }
}