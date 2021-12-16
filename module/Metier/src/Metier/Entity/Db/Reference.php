<?php

namespace Metier\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Reference implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var Metier */
    private $metier;
    /** @var Referentiel */
    private $referentiel;
    /** @var string */
    private $code;
    /** @var string */
    private $lien;
    /** @var integer */
    private $page;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Metier
     */
    public function getMetier()
    {
        return $this->metier;
    }

    /**
     * @param Metier $metier
     * @return Reference
     */
    public function setMetier(Metier $metier)
    {
        $this->metier = $metier;
        return $this;
    }

    /**
     * @return Referentiel
     */
    public function getReferentiel()
    {
        return $this->referentiel;
    }

    /**
     * @param Referentiel $referentiel
     * @return Reference
     */
    public function setReferentiel(Referentiel $referentiel)
    {
        $this->referentiel = $referentiel;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        if ($this->getReferentiel() !== null AND $this->getReferentiel()->getType() === Referentiel::VIDE) return $this->getReferentiel()->getLibelleCourt();
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return Reference
     */
    public function setCode(?string $code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getLien()
    {
        return $this->lien;
    }

    /**
     * @param string|null $lien
     * @return Reference
     */
    public function setLien(?string $lien)
    {
        $this->lien = $lien;
        return $this;
    }

    /**
     * @return  string
     */
    public function getTitre()
    {
        return $this->referentiel->getLibelleCourt() . " - " . $this->getCode();
    }

    /**
     * @return string
     */
    public function getUrl()
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

    /**
     * @return int|null
     */
    public function getPage() : ?int
    {
        return $this->page;
    }

    /**
     * @param int|null $page
     * @return Reference
     */
    public function setPage(?int $page) : Reference
    {
        $this->page = $page;
        return $this;
    }


}