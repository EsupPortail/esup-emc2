<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Activite;
use DateTime;
use Doctrine\Common\Collections\Collection;
use Element\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use Application\Entity\Db\Traits\HasSourceTrait;
use Element\Entity\Db\Traits\HasApplicationCollectionTrait;
use Element\Entity\Db\Traits\HasCompetenceCollectionTrait;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Formation implements HistoriqueAwareInterface,
    HasDescriptionInterface, HasSourceInterface,
    HasApplicationCollectionInterface, HasCompetenceCollectionInterface
{
    use HasDescriptionTrait;
    use HasSourceTrait;
    use HistoriqueAwareTrait;
    use HasApplicationCollectionTrait;
    use HasCompetenceCollectionTrait;

    const RATTACHEMENT_PREVENTION = 'prévention';
    const RATTACHEMENT_BIBLIOTHEQUE = 'bibliotheque';

    const TYPE_PRESENTIEL = 'Présentiel';
    const TYPE_DISTANCIEL = 'Distanciel';
    const TYPE_MIXTE      = 'Mixte';
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

    private Collection $missions;
    private Collection $instances;
    private Collection $abonnements;
    private Collection $plans;

    private ?string $rattachement = null;

    public function __construct()
    {
        $this->missions = new ArrayCollection();
        $this->instances = new ArrayCollection();
        $this->abonnements = new ArrayCollection();
        $this->plans = new ArrayCollection();
    }

    public static function getAnnee(?DateTime $date = null) : ?int
    {
        if ($date === null) $date = new DateTime();
        if ((int) $date->format("m") > 8) return (int) $date->format('Y');
        return ((int) $date->format('Y') - 1);
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    /**
     * @param string|null $libelle
     * @return Formation
     */
    public function setLibelle(?string $libelle) : Formation
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLien() : ?string
    {
        return $this->lien;
    }

    /**
     * @param string|null $lien
     * @return Formation
     */
    public function setLien(?string $lien) : Formation
    {
        $this->lien = $lien;
        return $this;
    }

    /**
     * @return FormationGroupe|null
     */
    public function getGroupe() : ?FormationGroupe
    {
        return $this->groupe;
    }

    /**
     * @param FormationGroupe|null $groupe
     * @return Formation
     */
    public function setGroupe(?FormationGroupe $groupe) : Formation
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
     * @return Activite[]
     */
    public function getMissions() : array
    {
        return $this->missions->toArray();
    }

    /**
     * @param Activite $mission
     * @return Formation
     */
    public function addMission(Activite $mission) : Formation
    {
        $this->missions->add($mission);
        return $this;
    }

    /**
     * @param Activite $mission
     * @return Formation
     */
    public function removeMission(Activite $mission) : Formation
    {
        $this->missions->removeElement($mission);
        return $this;
    }

    /**
     * @param Formation[] $formations
     * @return array
     */
    public static function generateOptions(array $formations) : array
    {
        $groupes = [];
        foreach ($formations as $formation) $groupes[($formation->getGroupe()) ? $formation->getGroupe()->getLibelle() : "Sans groupe"][] = $formation;

        $options = [];
        foreach ($groupes as $libelle => $liste) {
            $optionsoptions = [];
            usort($liste, function (Formation $a, Formation $b) {
                return $a->getLibelle() > $b->getLibelle();
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

    /** Formation Instances *******************************************************************************************/

    /**
     * @return FormationInstance[]
     */
    public function getInstances() : array
    {
        return $this->instances->toArray();
    }

    /**
     * @return FormationAbonnement[]
     */
    public function getAbonnements() : array
    {
        return $this->abonnements->toArray();
    }

    /** GESTION DES PLANS DE PLAN DE FORMATION *******************************************************/

    /**
     * @return PlanDeFormation[]
     */
    public function getPlans(): array
    {
        return $this->plans->toArray();
    }

    public function addPlanDeForamtion(PlanDeFormation $plan) : void
    {
        $this->plans->add($plan);
    }

    public function removePlanDeFormation(PlanDeFormation  $plan) : void
    {
        $this->plans->removeElement($plan);
    }

    public function hasPlanDeFormation(PlanDeFormation $plan) : bool
    {
        return $this->plans->contains($plan);
    }
}