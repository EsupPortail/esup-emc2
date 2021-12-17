<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Application\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Application\Entity\Db\Traits\HasApplicationCollectionTrait;
use Application\Entity\Db\Traits\HasCompetenceCollectionTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Formation\Entity\Db\Interfaces\HasFormationCollectionInterface;
use Formation\Entity\Db\Traits\HasFormationCollectionTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Activite implements HistoriqueAwareInterface,
    HasApplicationCollectionInterface, HasCompetenceCollectionInterface, HasFormationCollectionInterface
{
    use HistoriqueAwareTrait;
    use HasApplicationCollectionTrait;
    use HasCompetenceCollectionTrait;
    use HasFormationCollectionTrait;

    /** @var int */
    private $id;
    /** @var ArrayCollection */
    private $libelles;
    /** @var ArrayCollection */
    private $descriptions;
    /** @var ArrayCollection (FicheMetier) */
    private $fiches;
    /** @var ArrayCollection (Domaines) */
    private $domaines;

    /** @var NiveauEnveloppe|null */
    private $niveaux;

    public function __construct()
    {
        $this->libelles = new ArrayCollection();
        $this->descriptions = new ArrayCollection();
        $this->applications = new ArrayCollection();
        $this->competences = new ArrayCollection();
        $this->formations = new ArrayCollection();
        $this->fiches = new ArrayCollection();
        $this->domaines = new ArrayCollection();
    }
    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /** LIBELLE *******************************************************************************************************/

    /**
     * @return ActiviteLibelle|null
     */
    public function getCurrentActiviteLibelle() : ?ActiviteLibelle
    {
        /** @var ActiviteLibelle $activiteLibelle */
        foreach ($this->libelles as $activiteLibelle) {
            if ($activiteLibelle->estNonHistorise()) return $activiteLibelle;
        }
        return null;
    }

    /**
     * @return string
     */
    public function getLibelle() : string
    {
        $libelle = null;
        /** @var ActiviteLibelle $instance */
        foreach ($this->libelles as $instance) {
            if ($instance->getHistoDestruction() === null) {
                if ($libelle === null) return $instance->getLibelle();
                else return "<span class='probleme'><strong>PLUSIEURS LIBELLÉS ACTIFS !</strong></span>";
            }
        }
        return  "<span class='probleme'><strong>AUCUN LIBELLÉ !</strong></span>";
    }

    /** DESCRIPTION ***************************************************************************************************/

    /**
     * @return string
     */
    public function getDescription() : string
    {
        $desctiptions = $this->getDescriptions();
        $desctiption  = '<ul>';
        foreach ($desctiptions as $item) $desctiption .= '<li>' . $item->getLibelle() .'</li>';
        $desctiption .= '</ul>';
        return $desctiption;
    }

    /**
     * @return ActiviteDescription[]
     */
    public function getDescriptions() : array
    {
        $descriptions = [];
        /** @var ActiviteDescription $activiteDescription */
        foreach ($this->descriptions as $activiteDescription) {
            if ($activiteDescription->estNonHistorise()) $descriptions[] = $activiteDescription;
        }
        usort($descriptions, function (ActiviteDescription $a, ActiviteDescription $b) { return $a->getOrdre()>$b->getOrdre();});
        return $descriptions;
    }

    /**
     * @return Activite
     */
    public function clearDescriptions() : Activite
    {
        $this->descriptions->clear();
        return $this;
    }

    /** APPLICATIONS - VOIR HasApplicationCollectionTrait *************************************************************/
    /** COMPETENCES - VOIR HasCompetenceCollectionTrait ***************************************************************/
    /** FORMATIONS - VOIR HasFormationCollectionTrait ***************************************************************/

    /** FORMATIONS ****************************************************************************************************/
//
//    /**
//     * @return ArrayCollection (ActiviteFormation)
//     */
//    public function getFormationsCollection()
//    {
//        return $this->formations;
//    }
//
//    /**
//     * @return Formation[]
//     */
//    public function getFormations()
//    {
//        $formations = [];
//        /** @var ActiviteFormation $activiteFormation */
//        foreach ($this->formations as $activiteFormation) {
//            if ($activiteFormation->estNonHistorise()) $formations[] = $activiteFormation->getFormation();
//        }
//        return $formations;
//    }
//
//    /**
//     * @param Formation $formation
//     * @return boolean
//     */
//    public function hasFormation(Formation $formation)
//    {
//        /** @var ActiviteFormation $activiteFormation */
//        foreach ($this->formations as $activiteFormation) {
//            if ($activiteFormation->estNonHistorise() AND $activiteFormation->getFormation() === $formation) return true;
//        }
//        return false;
//    }

    /** FICHE METIER **************************************************************************************************/

    /**
     * @return ArrayCollection (FicheMetier)
     */
    public function getFichesMetiers()
    {
        return $this->fiches;
    }

    /** Domaine **************************************************************************************************/

    /**
     * @return ArrayCollection (Domaine)
     */
    public function getDomaines()
    {
        return $this->domaines;
    }

    /** ENVELOPPE DE NIVEAUX ******************************************************************************************/

    /**
     * @return NiveauEnveloppe|null
     */
    public function getNiveaux(): ?NiveauEnveloppe
    {
        return $this->niveaux;
    }

    /**
     * @param NiveauEnveloppe|null $niveaux
     * @return Activite
     */
    public function setNiveaux(?NiveauEnveloppe $niveaux): Activite
    {
        $this->niveaux = $niveaux;
        return $this;
    }



}