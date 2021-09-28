<?php

namespace Mailing\Model\Db;

use UnicaenRenderer\Entity\Db\Content;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class MailType {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelle;
    /** @var string */
    private $description;
    /** @var integer */
    private $actif;
    /** @var Content */
    private $contenu;

    /** @var string */
    private $sujet;
    /** @var string */
    private $corps;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return MailType
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return MailType
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return MailType
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getSujet()
    {
        return $this->sujet;
    }

    /**
     * @param string $sujet
     * @return MailType
     */
    public function setSujet($sujet)
    {
        $this->sujet = $sujet;
        return $this;
    }

    /**
     * @return string
     */
    public function getCorps()
    {
        return $this->corps;
    }

    /**
     * @param string $corps
     * @return MailType
     */
    public function setCorps($corps)
    {
        $this->corps = $corps;
        return $this;
    }

    /**
     * @return int
     */
    public function getActif()
    {
        return $this->actif;
    }

    /**
     * @param int $actif
     * @return MailType
     */
    public function setActif($actif)
    {
        $this->actif = $actif;
        return $this;
    }

    public function isActif() {
        return ($this->actif === 1);
    }

    /**
     * @return Content|null
     */
    public function getContenu(): ?Content
    {
        return $this->contenu;
    }

    /**
     * @param Content|null $contenu
     * @return MailType
     */
    public function setContenu(?Content $contenu): MailType
    {
        $this->contenu = $contenu;
        return $this;
    }


}