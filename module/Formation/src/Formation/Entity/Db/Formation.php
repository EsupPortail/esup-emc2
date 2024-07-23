<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use Application\Entity\Db\Traits\HasSourceTrait;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Element\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Element\Entity\Db\Traits\HasApplicationCollectionTrait;
use Element\Entity\Db\Traits\HasCompetenceCollectionTrait;
use FicheMetier\Entity\Db\Mission;
use Formation\Provider\Etat\SessionEtats;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Formation implements HistoriqueAwareInterface,
    HasDescriptionInterface,
    HasApplicationCollectionInterface, HasCompetenceCollectionInterface,
    HasSourceInterface
{
    use HasDescriptionTrait;
    use HistoriqueAwareTrait;
    use HasApplicationCollectionTrait;
    use HasCompetenceCollectionTrait;
    use HasSourceTrait;

    const RATTACHEMENT_PREVENTION = 'prévention';
    const RATTACHEMENT_BIBLIOTHEQUE = 'bibliotheque';

    const TYPE_PRESENTIEL = 'Présentiel';
    const TYPE_DISTANCIEL = 'Distanciel';
    const TYPE_MIXTE = 'Mixte';
    const TYPES = [
        Formation::TYPE_PRESENTIEL => 'Formation en présentiel',
        Formation::TYPE_DISTANCIEL => 'Formation en distanciel',
        Formation::TYPE_MIXTE => 'Formation en présentiel/distanciel'
    ];

    private ?int $id = -1;
    private ?string $libelle = null;
    private ?string $lien = null;
    private ?FormationGroupe $groupe = null;
    private bool $affichage = true;

    // information sur l'action de formation
    private ?string $type = null;
    private ?string $objectifs = null;
    private ?string $programme = null;
    private ?string $prerequis = null;
    private ?string $public = null;
    private ?string $complement = null;
    private ?ActionType $actionType = null;

    private Collection $missions;
    private Collection $instances;
    private Collection $abonnements;
    private Collection $domaines;
    private Collection $plans;

    private ?string $rattachement = null;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
        $this->instances = new ArrayCollection();
        $this->abonnements = new ArrayCollection();
        $this->plans = new ArrayCollection();
        $this->domaines = new ArrayCollection();
    }

    public static function getAnnee(?DateTime $date = null): ?int
    {
        if ($date === null) $date = new DateTime();
        $month = (int)$date->format("m");
        $year = (int)$date->format("Y");
        if ($month > 8) return $year;
        return ($year - 1);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    /**
     * @param string|null $libelle
     * @return Formation
     */
    public function setLibelle(?string $libelle): Formation
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLien(): ?string
    {
        return $this->lien;
    }

    /**
     * @param string|null $lien
     * @return Formation
     */
    public function setLien(?string $lien): Formation
    {
        $this->lien = $lien;
        return $this;
    }

    /**
     * @return FormationGroupe|null
     */
    public function getGroupe(): ?FormationGroupe
    {
        return $this->groupe;
    }

    /**
     * @param FormationGroupe|null $groupe
     * @return Formation
     */
    public function setGroupe(?FormationGroupe $groupe): Formation
    {
        $this->groupe = $groupe;
        return $this;
    }

    /**
     * @return bool
     */
    public function getAffichage(): bool
    {
        return $this->affichage;
    }

    /**
     * @param bool $affichage
     */
    public function setAffichage(bool $affichage): void
    {
        $this->affichage = $affichage;
    }

    /**
     * @return Mission[]
     */
    public function getMissions(): array
    {
        return $this->missions->toArray();
    }

    /**
     * @param Mission $mission
     * @return Formation
     */
    public function addMission(Mission $mission): Formation
    {
        $this->missions->add($mission);
        return $this;
    }

    /**
     * @param Mission $mission
     * @return Formation
     */
    public function removeMission(Mission $mission): Formation
    {
        $this->missions->removeElement($mission);
        return $this;
    }

    /**
     * @param Formation[] $formations
     * @return array
     */
    public static function generateOptions(array $formations): array
    {
        $groupes = [];
        foreach ($formations as $formation) $groupes[($formation->getGroupe()) ? $formation->getGroupe()->getLibelle() : "Sans groupe"][] = $formation;

        $options = [];
        foreach ($groupes as $libelle => $liste) {
            $optionsoptions = [];
            usort($liste, function (Formation $a, Formation $b) {
                return $a->getLibelle() <=> $b->getLibelle();
            });
            foreach ($liste as $formation) {
                $optionsoptions[] = [
                    'value' => $formation->getId(),
                    'label' => $formation->getLibelle(),
                ];
            }
            $array = [
                'label' => $libelle,
                'options' => $optionsoptions,
            ];
            $options[] = $array;
        }
        return $options;
    }


    public function getRattachement(): ?string
    {
        return $this->rattachement;
    }

    public function setRattachement(?string $rattachement): void
    {
        $this->rattachement = $rattachement;
    }

    /** INFORMATION SUR L'ACTION DE FORMATION *************************************************************************/

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getObjectifs(): ?string
    {
        return $this->objectifs;
    }

    public function setObjectifs(?string $objectifs): void
    {
        $this->objectifs = $objectifs;
    }

    public function getProgramme(): ?string
    {
        return $this->programme;
    }

    public function setProgramme(?string $programme): void
    {
        $this->programme = $programme;
    }

    public function getPrerequis(): ?string
    {
        return $this->prerequis;
    }

    public function setPrerequis(?string $prerequis): void
    {
        $this->prerequis = $prerequis;
    }

    public function getPublic(): ?string
    {
        return $this->public;
    }

    public function setPublic(?string $public): void
    {
        $this->public = $public;
    }

    public function getComplement(): ?string
    {
        return $this->complement;
    }

    public function setComplement(?string $complement): void
    {
        $this->complement = $complement;
    }

    public function getActionType(): ?ActionType
    {
        return $this->actionType;
    }

    public function setActionType(?ActionType $actionType): void
    {
        $this->actionType = $actionType;
    }

    /** Formation Instances *******************************************************************************************/

    /**
     * @return FormationInstance[]
     */
    public function getInstances(): array
    {
        return $this->instances->toArray();
    }


    /** @return FormationInstance[] */
    public function getSessionsWithEtats(array $etatCodes): array
    {
        $sessions = $this->getInstances();
        $sessions = array_filter($sessions, function (FormationInstance $a) use ($etatCodes) {
            return in_array($a->getEtatActif()->getType()->getCode(), $etatCodes);
        });
        return $sessions;
    }

    /** @return FormationInstance[] */
    public function getSessionsOuvertes(): array
    {
        $sessions = $this->getSessionsWithEtats(SessionEtats::ETATS_OUVERTS);
        return $sessions;
    }

    /** GESTION DES ABONNEMENTS ***************************************************************************************/

    /** @return FormationAbonnement[] */
    public function getAbonnements(): array
    {
        return $this->abonnements->toArray();
    }

    public function getAbonnementByAgent(?Agent $agent): ?FormationAbonnement
    {
        if ($agent === null) return null;
        foreach ($this->abonnements as $abonnement) {
            if ($abonnement->getAgent() === $agent) return $abonnement;
        }
        return null;
    }

    public function isPlanActif(): bool
    {
        foreach ($this->getPlans() as $plan) {
            if ($plan->isActif()) return true;
        }
        return false;
    }
    /** GESTION DES DOMAINES *************************************************************************/

    /**
     * @return Domaine[]
     */
    public function getDomaines(): array
    {
        return $this->domaines->toArray();
    }

    public function addDomaine(Domaine $domaine): void
    {
        $this->domaines->add($domaine);
    }

    public function removeDomaine(Domaine $domaine): void
    {
        $this->domaines->removeElement($domaine);
    }

    public function hasDomaine(Domaine $domaine): bool
    {
        return $this->domaines->contains($domaine);
    }

    /** GESTION DES PLANS DE PLAN DE FORMATION *******************************************************/

    /**
     * @return PlanDeFormation[]
     */
    public function getPlans(): array
    {
        return $this->plans->toArray();
    }

    public function addPlanDeForamtion(PlanDeFormation $plan): void
    {
        $this->plans->add($plan);
    }

    public function removePlanDeFormation(PlanDeFormation $plan): void
    {
        $this->plans->removeElement($plan);
    }

    public function hasPlanDeFormation(PlanDeFormation $plan): bool
    {
        return $this->plans->contains($plan);
    }

    /** MACROS ********************************************************************************************************/

    /** @noinspection PhpUnused  Macro: Formation#Complement */
    public function toStringComplement(): string
    {
        if ($this->getComplement() === null) return "";

        $text  = "<strong>Compléments à propos de l'action de formation :</strong>";
        $text .= $this->getComplement();
        return $text;
    }

}