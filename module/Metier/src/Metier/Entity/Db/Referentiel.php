<?php

namespace Metier\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Referentiel implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const WEB="web";
    const PDF="pdf";
    const VIDE="vide";

    private ?int $id = null;
    private ?string $libelleCourt = null;
    private ?string $libelleLong = null;
    private ?string $prefix = null;
    private ?string $type = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibelleCourt() : ?string
    {
        return $this->libelleCourt;
    }

    public function setLibelleCourt(?string $libelleCourt) : void
    {
        $this->libelleCourt = $libelleCourt;
    }

    public function getLibelleLong() : ?string
    {
        return $this->libelleLong;
    }

    public function setLibelleLong(?string $libelleLong) : void
    {
        $this->libelleLong = $libelleLong;
    }

    public function getPrefix() : ?string
    {
        return $this->prefix;
    }

    public function setPrefix(?string $prefix) : void
    {
        $this->prefix = $prefix;
    }

    public function getType() : ?string
    {
        return $this->type;
    }

    public function setType(?string $type) : void
    {
        $this->type = $type;
    }

}