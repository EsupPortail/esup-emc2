<?php

namespace Element\Entity\Db;

use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Application implements HistoriqueAwareInterface, HasDescriptionInterface {
    use HistoriqueAwareTrait;
    use HasDescriptionTrait;

    private ?int  $id = -1;
    private ?string $libelle = null;
    private ?string $url = null;
    private bool $actif = true;
    private ?ApplicationTheme $theme = null;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle) : void
    {
        $this->libelle = $libelle;
    }

    public function getUrl() : ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url) : void
    {
        $this->url = $url;
    }

    public function isActif() : bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif) : void
    {
        $this->actif = $actif;
    }

    public function getGroupe() : ?ApplicationTheme
    {
        return $this->theme;
    }

    public function setGroupe(?ApplicationTheme $groupe) : void
    {
        $this->theme = $groupe;
    }
}