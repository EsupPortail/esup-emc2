<?php

namespace Element\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Competence implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const SOURCE_REFERENS3 = 'REFERENS 3';
    const SOURCE_EMC2 = 'EMC2';

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var string */
    private $description;
    /** @var CompetenceType */
    private $type;
    /** @var CompetenceTheme */
    private $theme;
    /** @var string */
    private $source;
    /** @var integer */
    private $idSource;

    /** @var ArrayCollection (FicheMetier) */
    private $fiches;
    /** @var ArrayCollection (Activite) */
    private $activites;

    public function __construct()
    {
        $this->fiches = new ArrayCollection();
        $this->activites = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    /**
     * @param string|null $libelle
     * @return Competence
     */
    public function setLibelle(?string $libelle) : Competence
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
     * @return Competence
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return CompetenceType
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param CompetenceType $type
     * @return Competence
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param mixed $theme
     * @return Competence
     */
    public function setTheme($theme)
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * @return FicheMetier[]
     */
    public function getFichesMetiers()
    {
        return []; //$this->fiches->toArray();
    }

    /**
     * @return Activite[]
     */
    public function getActivites()
    {
        return []; //$this->activites->toArray();
    }

    /** SOURCES  ***************************************************************************************/

    /**
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * @param string $source
     * @return Competence
     */
    public function setSource(string $source): Competence
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getIdSource(): ?int
    {
        return $this->idSource;
    }

    /**
     * @param int $idSource
     * @return Competence
     */
    public function setIdSource(int $idSource): Competence
    {
        $this->idSource = $idSource;
        return $this;
    }


}