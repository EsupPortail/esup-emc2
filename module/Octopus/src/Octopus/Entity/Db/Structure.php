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
     * @return string
     */
    public function getSigle()
    {
        return $this->sigle;
    }

    /**
     * @return string
     */
    public function getLibelleCourt()
    {
        return $this->libelleCourt;
    }

    /**
     * @return string
     */
    public function getLibelleLong()
    {
        return $this->libelleLong;
    }

    /**
     * @return string
     */
    public function getAdresse()
    {
        return $this->adresse;
    }

    /**
     * @return string
     */
    public function getTelephone()
    {
        return $this->telephone;
    }

    /**
     * @return string
     */
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * @return StructureType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getCodeUai()
    {
        return $this->codeUai;
    }

    /**
     * @return string
     */
    public function getLogoContent()
    {
        return $this->logoContent;
    }

    /**
     * @return DateTime
     */
    public function getDateOuverture()
    {
        return $this->dateOuverture;
    }

    /**
     * @return DateTime
     */
    public function getDateFermeture()
    {
        return $this->dateFermeture;
    }

    /**
     * @return string
     */
    public function getTypeSupann()
    {
        return $this->typeSupann;
    }

}