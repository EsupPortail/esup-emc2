<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class SpecificiteActivite implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var SpecificitePoste */
    private $specificite;
    /** @var Activite */
    private $activite;
    /** @var string|null */
    private $retrait;
    /** @var string|null */
    private $description;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return SpecificitePoste|null
     */
    public function getSpecificite(): ?SpecificitePoste
    {
        return $this->specificite;
    }

    /**
     * @param SpecificitePoste $specificite
     * @return SpecificiteActivite
     */
    public function setSpecificite(SpecificitePoste $specificite): SpecificiteActivite
    {
        $this->specificite = $specificite;
        return $this;
    }

    /**
     * @return Activite|null
     */
    public function getActivite(): ?Activite
    {
        return $this->activite;
    }

    /**
     * @param Activite $activite
     * @return SpecificiteActivite
     */
    public function setActivite(Activite $activite): SpecificiteActivite
    {
        $this->activite = $activite;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRetrait(): ?string
    {
        return $this->retrait;
    }

    /**
     * @param string|null $retrait
     * @return SpecificiteActivite
     */
    public function setRetrait(?string $retrait): SpecificiteActivite
    {
        $this->retrait = $retrait;
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
     * @return SpecificiteActivite
     */
    public function setDescription(?string $description): SpecificiteActivite
    {
        $this->description = $description;
        return $this;
    }
}