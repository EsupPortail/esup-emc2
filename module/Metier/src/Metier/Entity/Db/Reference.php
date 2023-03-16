<?php

namespace Metier\Entity\Db;

use Metier\Entity\HasMetierInterface;
use Metier\Entity\HasMetierTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Reference implements HistoriqueAwareInterface, HasMetierInterface {
    use HistoriqueAwareTrait;
    use HasMetierTrait;

    private ?int $id = null;
    private ?Referentiel $referentiel = null;
    private ?string $code = null;
    private ?string $lien = null;
    private ?int $page = null;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getReferentiel() : ?Referentiel
    {
        return $this->referentiel;
    }

    public function setReferentiel(?Referentiel $referentiel) : void
    {
        $this->referentiel = $referentiel;
    }

    public function getCode() : ?string
    {
        if ($this->getReferentiel() !== null AND $this->getReferentiel()->getType() === Referentiel::VIDE) return $this->getReferentiel()->getLibelleCourt();
        return $this->code;
    }

    public function setCode(?string $code) : void
    {
        $this->code = $code;
    }

    public function getLien() : ?string
    {
        return $this->lien;
    }

    public function setLien(?string $lien) : void
    {
        $this->lien = $lien;
    }

    public function getTitre() : string
    {
        return $this->referentiel->getLibelleCourt() . " - " . $this->getCode();
    }

    public function getUrl() : string
    {
        switch($this->getReferentiel()->getType()) {
            case Referentiel::WEB :
                if ($this->getLien())                       return $this->getLien();
                if ($this->getReferentiel()->getPrefix())   return $this->referentiel->getPrefix() . $this->getCode();
                return "";
            case Referentiel::PDF :
                $url = "";
                if ($this->getReferentiel()->getPrefix())   $url = $this->getReferentiel()->getPrefix();
                if ($this->getLien())                       $url = $this->getLien();
                if ($this->getPage()) {
                    $url = $url . "#page=" . $this->getPage();
                }
                return $url;
        }
        return "";
    }

    public function getPage() : ?int
    {
        return $this->page;
    }

    public function setPage(?int $page) : void
    {
        $this->page = $page;
    }
}