<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Activite;
use DateTime;
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

    private int $id = -1;
    private ?string $libelle = null;
    private ?string $lien = null;
    private ?FormationGroupe $groupe = null;
    private bool $affichage = true;

    /** @var ArrayCollection */
    private $missions;
    /** @var ArrayCollection (FormationInstance) */
    private $instances;
    /** @var ArrayCollection (FormationAbonnement) */
    private $abonnements;


    public function __construct()
    {
        $this->missions = new ArrayCollection();
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

}