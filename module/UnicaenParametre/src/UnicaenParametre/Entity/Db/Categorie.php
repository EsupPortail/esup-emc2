<?php

namespace UnicaenParametre\Entity\Db;

class Categorie
{
    const DEFAULT_ORDER = 9999;

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelle;
    /** @var string */
    private $description;
    /** @var integer */
    private $ordre;

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
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return Categorie
     */
    public function setCode(?string $code): Categorie
    {
        $this->code = $code;
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
     * @return Categorie
     */
    public function setLibelle(?string $libelle): Categorie
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
     * @return Categorie
     */
    public function setDescription(?string $description): Categorie
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    /**
     * @param int $ordre
     * @return Categorie
     */
    public function setOrdre(int $ordre = Categorie::DEFAULT_ORDER): Categorie
    {
        $this->ordre = $ordre;
        return $this;
    }

}

