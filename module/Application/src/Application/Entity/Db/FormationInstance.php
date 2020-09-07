<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FormationInstance implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var Formation */
    private $formation;
    /** @var string */
    private $complement;

    /** @var integer */
    private $nbPlacePrincipale;
    /** @var integer */
    private $nbPlaceComplementaire;

    /** @var ArrayCollection (FormationInstanceJournee) */
    private $journees;
    /** @var ArrayCollection (FormationInstanceInscrit) */
    private $inscrits;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Formation
     */
    public function getFormation()
    {
        return $this->formation;
    }

    /**
     * @param Formation $formation
     * @return FormationInstance
     */
    public function setFormation($formation)
    {
        $this->formation = $formation;
        return $this;
    }

    /**
     * @return string
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * @param string $complement
     * @return FormationInstance
     */
    public function setComplement($complement)
    {
        $this->complement = $complement;
        return $this;
    }

    /** PLACE SUR LISTE  **********************************************************************************************/

    /**
     * @return int
     */
    public function getNbPlacePrincipale()
    {
        return $this->nbPlacePrincipale;
    }

    /**
     * @param int $nbPlacePrincipale
     * @return FormationInstance
     */
    public function setNbPlacePrincipale(int $nbPlacePrincipale)
    {
        $this->nbPlacePrincipale = $nbPlacePrincipale;
        return $this;
    }

    /**
     * @return int
     */
    public function getNbPlaceComplementaire()
    {
        return $this->nbPlaceComplementaire;
    }

    /**
     * @param int $nbPlaceComplementaire
     * @return FormationInstance
     */
    public function setNbPlaceComplementaire(int $nbPlaceComplementaire)
    {
        $this->nbPlaceComplementaire = $nbPlaceComplementaire;
        return $this;
    }

    /** JOURNEE *******************************************************************************************************/

    public function getJournees()
    {
        if ($this->journees === null) return null;
        return $this->journees->toArray();
    }

    public function getDebut()
    {
        $minimum = null;
        foreach ($this->journees as $journee) {
            if ($journee->estNonHistorise()) {
                $split = explode("/", $journee->getJour());
                $reversed = $split[2] . "/" . $split[1] . "/" . $split[0];
                if ($minimum === null or $reversed < $minimum) $minimum = $reversed;
            }
        }
        if ($minimum !== null) {
            $split = explode("/",$minimum);
            $minimum = $split[2]."/".$split[1]."/".$split[0];
        }
        return $minimum;
    }

    public function getFin()
    {
        $maximum = null;
        /** @var FormationInstanceJournee $journee */
        foreach ($this->journees as $journee) {
            if ($journee->estNonHistorise()) {
                $split = explode("/",$journee->getJour());
                $reversed = $split[2]."/".$split[1]."/".$split[0];
                if ($maximum === null OR  $reversed > $maximum) $maximum = $reversed;
            }
        }
        if ($maximum !== null) {
            $split = explode("/",$maximum);
            $maximum = $split[2]."/".$split[1]."/".$split[0];
        }
        return $maximum;
    }

    /** INSCRIT *******************************************************************************************************/

    /**
     * @return FormationInstanceInscrit[]
     */
    public function getInscrits()
    {
        if ($this->inscrits === null) return null;
        return $this->inscrits->toArray();
    }

    /**
     * @return FormationInstanceInscrit[]
     */
    public function getListePrincipale()
    {
        if ($this->inscrits === null) return null;
        $array = array_filter($this->inscrits->toArray(), function (FormationInstanceInscrit $a) { return $a->getListe() === FormationInstanceInscrit::PRINCIPALE;});
        usort($array, function (FormationInstanceInscrit $a, FormationInstanceInscrit $b) { return $a->getAgent()->getDenomination() > $b->getAgent()->getDenomination();});
        return $array;
    }

    /**
     * @return FormationInstanceInscrit[]
     */
    public function getListeComplementaire()
    {
        if ($this->inscrits === null) return null;
        $array =  array_filter($this->inscrits->toArray(), function (FormationInstanceInscrit $a) { return $a->getListe() === FormationInstanceInscrit::COMPLEMENTAIRE;});
        usort($array, function (FormationInstanceInscrit $a, FormationInstanceInscrit $b) { return $a->getHistoCreation() > $b->getHistoCreation();});
        return $array;
    }

    /**
     * @return FormationInstanceInscrit[]
     */
    public function getListeHistorisee()
    {
        if ($this->inscrits === null) return null;
        $array=  array_filter($this->inscrits->toArray(), function (FormationInstanceInscrit $a) { return $a->estHistorise();});
        usort($array, function (FormationInstanceInscrit $a, FormationInstanceInscrit $b) { return $a->getAgent()->getDenomination() > $b->getAgent()->getDenomination();});
        return $array;
    }

    /**
     * @return bool
     */
    public function isListePrincipaleComplete()
    {
        if ($this->inscrits === null) return false;
        $array =  array_filter($this->inscrits->toArray(), function (FormationInstanceInscrit $a) { return $a->getListe() === FormationInstanceInscrit::PRINCIPALE;});
        return (count($array) >= $this->getNbPlacePrincipale());
    }

    /**
     * @return bool
     */
    public function isListeComplementaireComplete()
    {
        if ($this->inscrits === null) return false;
        $array =  array_filter($this->inscrits->toArray(), function (FormationInstanceInscrit $a) { return $a->getListe() === FormationInstanceInscrit::COMPLEMENTAIRE;});
        return (count($array) >= $this->getNbPlaceComplementaire());
    }

    public function getListeDisponible()
    {
        $liste = null;
        if ($liste === null AND ! $this->isListePrincipaleComplete()) $liste = FormationInstanceInscrit::PRINCIPALE;
        if ($liste === null AND ! $this->isListeComplementaireComplete()) $liste = FormationInstanceInscrit::COMPLEMENTAIRE;
        return $liste;
    }
}