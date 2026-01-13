<?php

namespace Carriere\Entity\Db;

use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use Carriere\Entity\Db\Interface\HasCategorieInterface;
use Carriere\Entity\Db\Trait\HasCategorieTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Niveau implements HistoriqueAwareInterface, HasDescriptionInterface, HasCategorieInterface {
    use HistoriqueAwareTrait;
    use HasDescriptionTrait;
    use HasCategorieTrait;

    private ?int $id = null;
    private ?int $niveau = null;
    private ?string $etiquette = null;
    private ?string $libelle = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getEtiquette(): ?string
    {
        return $this->etiquette;
    }

    public function setEtiquette(string $etiquette): void
    {
        $this->etiquette = $etiquette;
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(int $niveau): void
    {
        $this->niveau = $niveau;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): void
    {
        $this->libelle = $libelle;
    }
}