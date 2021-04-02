<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class MaitriseNiveau implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $type;
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
    public function getType(): ?string
    {
        return $this->type;
    }

    /**
     * @param string|null $type
     * @return MaitriseNiveau
     */
    public function setType(?string $type): MaitriseNiveau
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
     * @return MaitriseNiveau
     */
    public function setLibelle(?string $libelle): MaitriseNiveau
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
     * @return MaitriseNiveau
     */
    public function setNiveau(?int $niveau): MaitriseNiveau
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
     * @return MaitriseNiveau
     */
    public function setDescription(?string $description): MaitriseNiveau
    {
        $this->description = $description;
        return $this;
    }

}