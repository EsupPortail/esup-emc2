<?php

namespace Carriere\Entity\Db;

use Application\Entity\Db\AgentGrade;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;

class Corps
{
    use DbImportableAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelleCourt;
    /** @var string */
    private $libelleLong;
    /** @var string */
    private $categorie;
    /** @var DateTime */
    private $histo;
    /** @var Niveau */
    private $niveau;
    /** @var NiveauEnveloppe */
    private $niveaux;

    /** @var ArrayCollection (AgentGrade) */
    private $agentGrades;

    /**
     * @return int
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return Corps
     */
    public function setId(int $id) : Corps
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode() : ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return Corps
     */
    public function setCode(?string $code) : Corps
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelleCourt() : ?string
    {
        return $this->libelleCourt;
    }

    /**
     * @param string|null $libelleCourt
     * @return Corps
     */
    public function setLibelleCourt(?string $libelleCourt) : Corps
    {
        $this->libelleCourt = $libelleCourt;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelleLong(): ?string
    {
        return $this->libelleLong;
    }

    /**
     * @param string|null $libelleLong
     * @return Corps
     */
    public function setLibelleLong(?string $libelleLong) : Corps
    {
        $this->libelleLong = $libelleLong;
        return $this;
    }

    /**
     * @return string
     */
    public function getCategorie() : ?string
    {
        return $this->categorie;
    }

    /**
     * @param string|null $categorie
     * @return Corps
     */
    public function setCategorie(?string $categorie) : Corps
    {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     * @return Niveau|null
     */
    public function getNiveau() : ?Niveau
    {
        return $this->niveau;
    }

    /**
     * @param Niveau|null $niveau
     * @return Corps
     */
    public function setNiveau(?Niveau $niveau) : Corps
    {
        $this->niveau = $niveau;
        return $this;
    }

    /**
     * @return NiveauEnveloppe
     */
    public function getNiveaux(): ?NiveauEnveloppe
    {
        return $this->niveaux;
    }

    /**
     * @param NiveauEnveloppe|null $niveaux
     * @return Corps
     */
    public function setNiveaux(?NiveauEnveloppe $niveaux): Corps
    {
        $this->niveaux = $niveaux;
        return $this;
    }

    /**
     * @return AgentGrade[]
     */
    public function getAgentGrades() : array
    {
        return $this->agentGrades->toArray();
    }


    public function __toString() : string
    {
        return $this->getLibelleCourt();
    }

    /**
     * @return string
     */
    public function generateTooltip() : string
    {
        $text  = "";
        $text .= "Libelle court : <strong>". $this->getLibelleCourt() . "</strong>";
        $text .= "<br/>";
        $text .= "Libelle long : <strong>". $this->getLibelleLong() . "</strong>";
        $text .= "<br/>";
        $text .= "Code : <strong>". $this->getCode() . "</strong>";
        return $text;
    }
}