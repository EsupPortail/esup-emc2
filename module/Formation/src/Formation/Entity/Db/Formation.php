<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Activite;
use Application\Entity\Db\Application;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Formation implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var string */
    private $description;
    /** @var string */
    private $lien;
    /** @var FormationGroupe */
    private $groupe;
    /** @var FormationTheme */
    private $theme;

    /** @var ArrayCollection */
    private $applications;
    /** @var ArrayCollection */
    private $missions;
    /** @var ArrayCollection (FormationInstance) */
    private $instances;


    public function __construct()
    {
        $this->applications = new ArrayCollection();
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
    public function getDescription() : ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Formation
     */
    public function setDescription(?string $description) : Formation
    {
        $this->description = $description;
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
     * @return FormationTheme|null
     */
    public function getTheme() : ?FormationTheme
    {
        return $this->theme;
    }

    /**
     * @param FormationTheme|null $theme
     * @return Formation
     */
    public function setTheme(?FormationTheme $theme) : Formation
    {
        $this->theme = $theme;
        return $this;
    }

    /**
     * @return Application[]
     */
    public function getApplications() : array
    {
        return $this->applications->toArray();
    }

    /**
     * @param Application|null $application
     * @return Formation
     */
    public function addApplication(?Application $application) : Formation
    {
        $this->applications->add($application);
        return $this;
    }

    /**
     * @param Application|null $application
     * @return Formation
     */
    public function removeApplication(?Application $application) : Formation
    {
        $this->applications->removeElement($application);
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
        $themes = [];
        foreach ($formations as $formation) $themes[($formation->getTheme()) ? $formation->getTheme()->getLibelle() : "Sans thème"][] = $formation;

        $options = [];
        foreach ($themes as $libelle => $liste) {
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