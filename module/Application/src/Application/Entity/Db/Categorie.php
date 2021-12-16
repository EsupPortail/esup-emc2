<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Categorie implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelle;
    /** @var ArrayCollection (Metier) */
    private $metiers;

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

    /**
     * @return array
     */
    public function getMetiers(): array
    {
        return $this->metiers->toArray();
    }

}