<?php

namespace Element\Entity\Db;

use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Niveau implements HistoriqueAwareInterface, HasDescriptionInterface {
    use HistoriqueAwareTrait;
    use HasDescriptionTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $type;
    /** @var string */
    private $libelle;
    /** @var integer */
    private $niveau;

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
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return Niveau
     */
    public function setType(?string $type): Niveau
    {
        $this->type = $type;
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
     * @param string|null $libelle
     * @return Niveau
     */
    public function setLibelle(?string $libelle): Niveau
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
     * @return Niveau
     */
    public function setNiveau(?int $niveau): Niveau
    {
        $this->niveau = $niveau;
        return $this;
    }

}