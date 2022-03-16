<?php

namespace Element\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class ApplicationTheme implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var string */
    private $couleur;
    /** @var string */
    private $ordre;
    /** @var ArrayCollection (Application) */
    private $applications;

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
     * @param string $libelle
     * @return ApplicationTheme
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string
     */
    public function getCouleur()
    {
        return $this->couleur;
    }

    /**
     * @param string $couleur
     * @return ApplicationTheme
     */
    public function setCouleur($couleur)
    {
        $this->couleur = $couleur;
        return $this;
    }

    /**
     * @return string
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * @param string $ordre
     * @return ApplicationTheme
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;
        return $this;
    }

    /**
     * @return Application[]
     */
    public function getApplications()
    {
        return $this->applications->toArray();
    }

    /**
     * @param Application $application
     * @return ApplicationTheme
     */
    public function addFormation($application)
    {
        $this->applications->add($application);
        return $this;
    }
    /**
     * @param Application $application
     * @return ApplicationTheme
     */
    public function removeFormation($application)
    {
        $this->applications->removeElement($application);
        return $this;
    }

    /**
     * @param Application $application
     * @return boolean
     */
    public function hasFormation($application)
    {
        return $this->applications->contains($application);
    }


}