<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class MaitriseNiveau implements HistoriqueAwareInterface, HasDescriptionInterface {
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

}