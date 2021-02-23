<?php

namespace Application\Entity\Db;

use DateTime;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FicheProfil implements HistoriqueAwareInterface {
    use DateTimeAwareTrait;
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var boolean */
    private $vancanceEmploi;
    /** @var FichePoste */
    private $ficheposte;
    /** @var Structure */
    private $structure;
    /** @var string */
    private $lieu;
    /** @var string */
    private $contexte;
    /** @var string */
    private $mission;
    /** @var string */
    private $niveau;
    /** @var string */
    private $contraintes;
    /** @var string */
    private $contrat;
    /** @var string */
    private $renumeration;
    /** @var DateTime */
    private $date;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return bool
     */
    public function isVacanceEmploi(): bool
    {
        return $this->vancanceEmploi;
    }

    /**
     * @param bool $vancanceEmploi
     * @return FicheProfil
     */
    public function setVancanceEmploi(bool $vancanceEmploi): FicheProfil
    {
        $this->vancanceEmploi = $vancanceEmploi;
        return $this;
    }



    /**
     * @return FichePoste|null
     */
    public function getFichePoste(): ?FichePoste
    {
        return $this->ficheposte;
    }

    /**
     * @param FichePoste $ficheposte
     * @return FicheProfil
     */
    public function setFichePoste(FichePoste $ficheposte): FicheProfil
    {
        $this->ficheposte = $ficheposte;
        return $this;
    }

    /**
     * @return Structure|null
     */
    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    /**
     * @param Structure $structure
     * @return FicheProfil
     */
    public function setStructure(Structure $structure): FicheProfil
    {
        $this->structure = $structure;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLieu(): ?string
    {
        return $this->lieu;
    }

    /**
     * @param string|null $lieu
     * @return FicheProfil
     */
    public function setLieu(?string $lieu): FicheProfil
    {
        $this->lieu = $lieu;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContexte(): ?string
    {
        return $this->contexte;
    }

    /**
     * @param string|null $contexte
     * @return FicheProfil
     */
    public function setContexte(?string $contexte): FicheProfil
    {
        $this->contexte = $contexte;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getMission(): ?string
    {
        return $this->mission;
    }

    /**
     * @param string|null $mission
     * @return FicheProfil
     */
    public function setMission(?string $mission): FicheProfil
    {
        $this->mission = $mission;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNiveau(): ?string
    {
        return $this->niveau;
    }

    /**
     * @param string|null $niveau
     * @return FicheProfil
     */
    public function setNiveau(?string $niveau): FicheProfil
    {
        $this->niveau = $niveau;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContraintes(): ?string
    {
        return $this->contraintes;
    }

    /**
     * @param string|null $contraintes
     * @return FicheProfil
     */
    public function setContraintes(?string $contraintes): FicheProfil
    {
        $this->contraintes = $contraintes;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getContrat(): ?string
    {
        return $this->contrat;
    }

    /**
     * @param string|null $contrat
     * @return FicheProfil
     */
    public function setContrat(?string $contrat): FicheProfil
    {
        $this->contrat = $contrat;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRenumeration(): ?string
    {
        return $this->renumeration;
    }

    /**
     * @param string|null $renumeration
     * @return FicheProfil
     */
    public function setRenumeration(?string $renumeration): FicheProfil
    {
        $this->renumeration = $renumeration;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return FicheProfil
     */
    public function setDate(DateTime $date): FicheProfil
    {
        $this->date = $date;
        return $this;
    }

    /** Predicat ******************************************************************************************************/

    /**
     * @param DateTime|null $date
     * @return bool
     */
    public function estEnCours(DateTime $date = null) : bool
    {
        if ($date === null) $date = $this->getDateTime();
        return ($this->date >= $date);
    }

    /** Fonction de mise en forme pour edition pdf ********************************************************************/

    public function getLieuAffichage()
    {
        if ($this->lieu === null) return null;
        $texte  = '<h2> Lieu </h2>';
        $texte .= $this->lieu;
        return $texte;
    }

    public function getContexteAffichage()
    {
        if ($this->contexte === null) return null;
        $texte  = '<h2> Contexte </h2>';
        $texte .= $this->contexte;
        return $texte;
    }

    public function getMissionAffichage()
    {
        if ($this->mission === null) return null;
        $texte  = '<h2> Mission principale du poste </h2>';
        $texte .= $this->mission;
        return $texte;
    }

    public function getNiveauAffichage()
    {
        if ($this->niveau === null) return null;
        $texte  = '<h2> Niveau requis </h2>';
        $texte .= $this->niveau;
        return $texte;
    }

    public function getContraintesAffichage()
    {
        if ($this->contraintes === null) return null;
        $texte  = '<h2> Contraintes liées au poste </h2>';
        $texte .= $this->contraintes;
        return $texte;
    }

    public function getVacanceEmploiAffichage()
    {
        if (!$this->isVacanceEmploi()) return null;
        $texte  = "<h2> Vacance d'emploi </h2>";
        return $texte;
    }

    public function getContratAffichage()
    {
        if ($this->isVacanceEmploi() OR $this->contrat === null) return null;
        $texte  = '<h2> Contrat </h2>';
        $texte .= $this->contrat;
        return $texte;
    }

    public function getRenumerationAffichage()
    {
        if ($this->isVacanceEmploi() OR $this->renumeration === null) return null;
        $texte  = '<h2> Rénumération </h2>';
        $texte .= $this->renumeration;
        return $texte;
    }

    public function getDateAffichage()
    {
        if ($this->date === null) return "N.C.";
        return  $this->date->format('d/m/Y');
    }
}