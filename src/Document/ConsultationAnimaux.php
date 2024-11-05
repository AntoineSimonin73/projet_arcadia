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

    #[MongoDB\Field(type: "int")]
    private $views;

    public function getId()
    {
        return $this->id;
    }

    public function getAnimalId()
    {
        return $this->animalId;
    }

    public function setAnimalId($animalId)
    {
        $this->animalId = $animalId;
    }

    public function getViews()
    {
        return $this->views;
    }

    public function setViews($views)
    {
        $this->views = $views;
    }

    public function incrementViews()
    {
        $this->views++;
    }
}