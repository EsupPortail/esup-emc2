<?php

namespace Octopus\Entity\Db;

use DateTime;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Structure {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $sigle;
    /** @var string */
    private $libelleCourt;
    /** @var string */
    private $libelleLong;
    /** @var string */
    private $adresse;
    /** @var string */
    private $telephone;
    /** @var string */
    private $fax;
    /** @var StructureType */
    private $type;
    /** @var string */
    private $codeUai;
    /** @var string */
    private $logoContent;
    /** @var DateTime */
    private $dateOuverture;
    /** @var DateTime */
    private $dateFermeture;
    /** @var string */
    private $typeSupann;

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
     * @return Structure
     */
    public function setCode($code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getSigle()
    {
        return $this->sigle;
    }

    /**
     * @param string $sigle
     * @return Structure
     */
    public function setSigle($sigle)
    {
        $this->sigle = $sigle;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }

    /**
     * @param string $libelleCourt
     * @return Structure
     */
    public function setLibelleCourt($libelleCourt)
    {
        $this->libelleCourt = $libelleCourt;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelleLong()
    {
        return $this->libelleLong;
    }

    /**
     * @param string $libelleLong
     * @return Structure
     */
    public function setLibelleLong($libelleLong)
    {
        $this->libelleLong = $libelleLong;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @param string $adresse
     * @return Structure
     */
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @param string $telephone
     * @return Structure
     */
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
        return $this;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @param string $fax
     * @return Structure
     */
    public function setFax($fax)
    {
        $this->fax = $fax;
        return $this;
    }

    /**
     * @return StructureType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param StructureType $type
     * @return Structure
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodeUai()
    {
        return $this->codeUai;
    }

    /**
     * @param string $codeUai
     * @return Structure
     */
    public function setCodeUai($codeUai)
    {
        $this->codeUai = $codeUai;
        return $this;
    }

    /**
     * @return string
     */
    public function getLogoContent()
    {
        return $this->logoContent;
    }

    /**
     * @param string $logoContent
     * @return Structure
     */
    public function setLogoContent($logoContent)
    {
        $this->logoContent = $logoContent;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateOuverture()
    {
        return $this->dateOuverture;
    }

    /**
     * @param DateTime $dateOuverture
     * @return Structure
     */
    public function setDateOuverture($dateOuverture)
    {
        $this->dateOuverture = $dateOuverture;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDateFermeture()
    {
        return $this->dateFermeture;
    }

    /**
     * @param DateTime $dateFermeture
     * @return Structure
     */
    public function setDateFermeture($dateFermeture)
    {
        $this->dateFermeture = $dateFermeture;
        return $this;
    }

    /**
     * @return string
     */
    public function getTypeSupann()
    {
        return $this->typeSupann;
    }

    /**
     * @param string $typeSupann
     * @return Structure
     */
    public function setTypeSupann($typeSupann)
    {
        $this->typeSupann = $typeSupann;
        return $this;
    }



}