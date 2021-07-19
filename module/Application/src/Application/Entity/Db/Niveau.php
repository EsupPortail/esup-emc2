<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Niveau implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var int */
    private $niveau;
    /** @var string */
    private $etiquette;
    /** @var string */
    private $libelle;
    /** @var string|null */
    private $description;

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

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Niveau
     */
    public function setDescription(?string $description): Niveau
    {
        $this->description = $description;
        return $this;
    }

}