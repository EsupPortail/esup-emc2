<?php

namespace UnicaenParametre\Entity\Db;

class Parametre
{
    const DEFAULT_ORDER = 9999;

    /** @var integer */
    private $id;
    /** @var Categorie */
    private $categorie;
    /** @var string */
    private $code;
    /** @var string */
    private $libelle;
    /** @var string */
    private $description;
    /** @var string */
    private $valeur;
    /** @var string */
    private $valeurs_possibles;
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
     * @return Categorie|null
     */
    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    /**
     * @param Categorie|null $categorie
     * @return Parametre
     */
    public function setCategorie(?Categorie $categorie): Parametre
    {
        $this->categorie = $categorie;
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
     * @return Parametre
     */
    public function setCode(?string $code): Parametre
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
     * @return Parametre
     */
    public function setLibelle(?string $libelle): Parametre
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
     * @return Parametre
     */
    public function setDescription(?string $description): Parametre
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getValeur(): ?string
    {
        return $this->valeur;
    }

    /**
     * @param string|null $valeur
     * @return Parametre
     */
    public function setValeur(?string $valeur): Parametre
    {
        $this->valeur = $valeur;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getValeursPossibles(): ?string
    {
        return $this->valeurs_possibles;
    }

    /**
     * @param string|null $valeurs_possibles
     * @return Parametre
     */
    public function setValeursPossibles(?string $valeurs_possibles): Parametre
    {
        $this->valeurs_possibles = $valeurs_possibles;
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
     * @return Parametre
     */
    public function setOrdre(int $ordre = Parametre::DEFAULT_ORDER): Parametre
    {
        $this->ordre = $ordre;
        return $this;
    }




}

