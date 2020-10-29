<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Categorie implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelle;

    /**
     * @return int
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getCode() : ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Categorie
     */
    public function setCode(string $code) : Categorie
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return Categorie
     */
    public function setLibelle(string $libelle) : Categorie
    {
        $this->libelle = $libelle;
        return $this;
    }


}