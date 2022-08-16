<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Activite;
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

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var string */
    private $lien;
    /** @var FormationGroupe */
    private $groupe;

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

}