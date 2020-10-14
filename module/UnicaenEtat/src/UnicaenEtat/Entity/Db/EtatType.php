<?php

namespace UnicaenEtat\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class EtatType implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelle;
    /** @var string */
    private $icone;
    /** @var string */
    private $couleur;

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
     * @param string $code
     * @return EtatType
     */
    public function setCode(string $code): EtatType
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
     * @param string $libelle
     * @return EtatType
     */
    public function setLibelle(string $libelle): EtatType
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIcone(): ?string
    {
        return $this->icone;
    }

    /**
     * @param string|null $icone
     * @return EtatType
     */
    public function setIcone(?string $icone): EtatType
    {
        $this->icone = $icone;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    /**
     * @param string|null $couleur
     * @return EtatType
     */
    public function setCouleur(?string $couleur): EtatType
    {
        $this->couleur = $couleur;
        return $this;
    }


}