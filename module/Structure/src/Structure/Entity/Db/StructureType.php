<?php

namespace Structure\Entity\Db;

class StructureType  {

    private ?int $id = -1 ;
    private ?string $code = null;
    private ?string $libelle = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode() : ?string
    {
        return $this->code;
    }

    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    public function __toString() : string
    {
        return ($this->getLibelle())?:"Aucun type";
    }

}