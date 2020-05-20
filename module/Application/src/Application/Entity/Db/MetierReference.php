<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class MetierReference implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var Metier */
    private $metier;
    /** @var MetierReferentiel */
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
     * @return MetierReference
     */
    public function setMetier(Metier $metier)
    {
        $this->metier = $metier;
        return $this;
    }

    /**
     * @return MetierReferentiel
     */
    public function getReferentiel()
    {
        return $this->referentiel;
    }

    /**
     * @param MetierReferentiel $referentiel
     * @return MetierReference
     */
    public function setReferentiel(MetierReferentiel $referentiel)
    {
        $this->referentiel = $referentiel;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return MetierReference
     */
    public function setCode($code)
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
     * @param string $lien
     * @return MetierReference
     */
    public function setLien($lien)
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
            case MetierReferentiel::WEB :
                if ($this->getLien())                       return $this->getLien();
                if ($this->getReferentiel()->getPrefix())   return $this->referentiel->getPrefix() . $this->getCode();
                return "";
            case MetierReferentiel::PDF :
                $url = "";
                if ($this->getReferentiel()->getPrefix())   $url = $this->getReferentiel()->getPrefix();
                if ($this->getLien())                       $url = $this->getLien();
                if ($this->getPage()) {
                    $url = $url . "#page=" . $this->getPage();
                }
                return $url;
        }
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @param int $page
     * @return MetierReference
     */
    public function setPage($page)
    {
        $this->page = $page;
        return $this;
    }


}