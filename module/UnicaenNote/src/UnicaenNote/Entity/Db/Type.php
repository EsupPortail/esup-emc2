<?php

namespace UnicaenNote\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Type implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var string */
    private $code;
    /** @var string */
    private $style;
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
     * @return Type
     */
    public function setLibelle(?string $libelle): Type
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return Type
     */
    public function setCode(?string $code): Type
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getStyle(): ?string
    {
        return $this->style;
    }

    /**
     * @param string|null $style
     * @return Type
     */
    public function setStyle(?string $style): Type
    {
        $this->style = $style;
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
     * @return Type
     */
    public function setDescription(?string $description): Type
    {
        $this->description = $description;
        return $this;
    }

}