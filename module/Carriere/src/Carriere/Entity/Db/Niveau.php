<?php

namespace Carriere\Entity\Db;

use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Niveau implements HistoriqueAwareInterface, HasDescriptionInterface {
    use HistoriqueAwareTrait;
    use HasDescriptionTrait;

    /** @var int */
    private $id;
    /** @var int */
    private $niveau;
    /** @var string */
    private $etiquette;
    /** @var string */
    private $libelle;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getEtiquette(): ?string
    {
        return $this->etiquette;
    }

    /**
     * @param string $etiquette
     * @return Niveau
     */
    public function setEtiquette(string $etiquette): Niveau
    {
        $this->etiquette = $etiquette;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    /**
     * @param int $niveau
     * @return Niveau
     */
    public function setNiveau(int $niveau): Niveau
    {
        $this->niveau = $niveau;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return Niveau
     */
    public function setLibelle(string $libelle): Niveau
    {
        $this->libelle = $libelle;
        return $this;
    }
}