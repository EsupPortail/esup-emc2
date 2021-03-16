<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class CompetenceMaitrise implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var integer */
    private $niveau;
    /** @var string */
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
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    /**
     * @param string|null $libelle
     * @return CompetenceMaitrise
     */
    public function setLibelle(?string $libelle): CompetenceMaitrise
    {
        $this->libelle = $libelle;
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
     * @param int|null $niveau
     * @return CompetenceMaitrise
     */
    public function setNiveau(?int $niveau): CompetenceMaitrise
    {
        $this->niveau = $niveau;
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
     * @return CompetenceMaitrise
     */
    public function setDescription(?string $description): CompetenceMaitrise
    {
        $this->description = $description;
        return $this;
    }

}