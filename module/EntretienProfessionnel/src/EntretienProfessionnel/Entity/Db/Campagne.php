<?php

namespace EntretienProfessionnel\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenAutoform\Entity\Db\Formulaire;
use UnicaenIndicateur\Entity\Interface\HasIndicateursInterface;
use UnicaenIndicateur\Entity\Trait\HasIndicateursTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Campagne implements HasPeriodeInterface, HistoriqueAwareInterface, HasIndicateursInterface {
    use HasPeriodeTrait;
    use HistoriqueAwareTrait;
    use HasIndicateursTrait;

    private ?int $id = -1;
    private ?string $annee = null;
    private ?Campagne $precede = null;
    private ?DateTime $dateCirculaire = null;
    private ?DateTime $dateEnPoste = null;
    private ?DateTime $dateFixe = null;
    private Collection $entretiens;

    private ?Formulaire $formulaireCREP = null;
    private ?Formulaire $formulaireCREF = null;

    public function __construct() {
        $this->indicateurs = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    /**
     * @return string|null (par exemple : 2019/2020)
     */
    public function getAnnee() : ?string
    {
        return $this->annee;
    }

    /**
     * @param string|null $annee (par exemple : 2019/2020)
     */
    public function setAnnee(?string $annee) : void
    {
        $this->annee = $annee;
    }

    /**
     * @return Campagne|null
     */
    public function getPrecede() : ?Campagne
    {
        return $this->precede;
    }

    public function setPrecede(?Campagne $precede) : void
    {
        $this->precede = $precede;
    }

    public function getDateCirculaire(): ?DateTime
    {
        return $this->dateCirculaire;
    }

    public function setDateCirculaire(?DateTime $dateCirculaire): void
    {
        $this->dateCirculaire = $dateCirculaire;
    }

    public function getDateEnPoste(): ?DateTime
    {
        return $this->dateEnPoste;
    }

    public function setDateEnPoste(?DateTime $dateEnPoste): void
    {
        $this->dateEnPoste = $dateEnPoste;
    }

    public function getDateFixe(): ?DateTime
    {
        return $this->dateFixe;
    }

    public function setDateFixe(?DateTime $dateFixe): void
    {
        $this->dateFixe = $dateFixe;
    }

    /**
     * @return EntretienProfessionnel[]
     */
    public function getEntretiensProfessionnels() : array
    {
        return $this->entretiens->toArray();
    }

    public function getFormulaireCREP(): ?Formulaire
    {
        return $this->formulaireCREP;
    }

    public function setFormulaireCREP(?Formulaire $formulaireCREP): void
    {
        $this->formulaireCREP = $formulaireCREP;
    }

    public function getFormulaireCREF(): ?Formulaire
    {
        return $this->formulaireCREF;
    }

    public function setFormulaireCREF(?Formulaire $formulaireCREF): void
    {
        $this->formulaireCREF = $formulaireCREF;
    }

    /** prédicats *****************************************************************************************************/

    public function isEligible(Agent $agent) : bool
    {
        $statuts = $agent->getStatuts($this->getDateDebut());
        foreach ($statuts as $statut) {
            if (!$statut->isEnseignant()) {
                if ($statut->isTitulaire()) return true;
                if ($statut->isCdi()) return true;
                if ($statut->isCdd()) {
                    return $agent->isContratLong() ;
                }
            }
        }
        return false;
    }

    /** Fonctions pour les macros *************************************************************************************/

    /** @noinspection  PhpUnused */
    public function generateTag() : string
    {
        return 'Campagne_' . $this->getId();
    }

    /** @noinspection  PhpUnused */
    public function toStringStatut() : string
    {
        if ($this->estEnCours()) return "ouverte";
        if ($this->estFini()) return "terminée";
        return "pas encore ouverte";
    }

    /** @noinspection  PhpUnused */
    public function getDateDebutToString() : string
    {
        return ($this->dateDebut)?$this->dateDebut->format('d/m/Y'):"N.C.";
    }

    /** @noinspection  PhpUnused */
    public function getDateFinToString() : string
    {
        return ($this->dateFin)?$this->dateFin->format('d/m/Y'):"N.C.";
    }

    /** @noinspection  PhpUnused */
    public function getDateCirculaireToString() : string
    {
        return ($this->dateCirculaire)?$this->dateCirculaire->format('d/m/Y'):"N.C.";
    }

    /** @noinspection  PhpUnused */
    public function getDatePrisePosteToString() : string
    {
        return ($this->dateEnPoste)?$this->dateEnPoste->format('d/m/Y'):"N.C.";
    }
}