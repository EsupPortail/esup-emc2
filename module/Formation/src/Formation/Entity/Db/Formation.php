<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Activite;
use Application\Entity\Db\Application;
use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Formation implements HistoriqueAwareInterface {
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return Formation
     */
    public function setLibelle(string $libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Formation
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getLien()
    {
        return $this->lien;
    }

    /**
     * @param string $lien
     * @return Formation
     */
    public function setLien(string $lien)
    {
        $this->lien = $lien;
        return $this;
    }

    /**
     * @return FormationGroupe
     */
    public function getGroupe()
    {
        return $this->groupe;
    }

    /**
     * @param FormationGroupe|null $groupe
     * @return Formation
     */
    public function setGroupe(?FormationGroupe $groupe)
    {
        $this->groupe = $groupe;
        return $this;
    }

    /**
     * @return FormationTheme
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * @param FormationTheme|null $theme
     * @return Formation
     */
    public function setTheme(?FormationTheme $theme)
    {
        $this->theme = $theme;
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
     * @param Application|null $application
     * @return Formation
     */
    public function addApplication(?Application $application)
    {
        $this->applications->add($application);
        return $this;
    }

    /**
     * @param Application|null $application
     * @return Formation
     */
    public function removeApplication(?Application $application)
    {
        $this->applications->removeElement($application);
        return $this;
    }

    /**
     * @return Activite[]
     */
    public function getMissions()
    {
        return $this->missions->toArray();
    }

    /**
     * @param Activite $mission
     * @return Formation
     */
    public function addMission(Activite $mission)
    {
        $this->missions->add($mission);
        return $this;
    }

    /**
     * @param Activite $mission
     * @return Formation
     */
    public function removeMission(Activite $mission)
    {
        $this->missions->removeElement($mission);
        return $this;
    }

    /**
     * @param Formation[] $formations
     * @return array
     */
    public static function generateOptions(array $formations)
    {
        $themes = [];
        foreach ($formations as $formation) $themes[($formation->getTheme())?$formation->getTheme()->getLibelle():"Sans thème"][] = $formation;

        $options = [];
        foreach ($themes as $libelle => $liste) {
            $optionsoptions = [];
            usort($liste, function (Formation $a, Formation $b) { return $a->getLibelle() > $b->getLibelle();});
            foreach ($liste as $formation) {
                $optionsoptions[] = [
                    'value' =>  $formation->getId(),
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

    public function getInstances()
    {
        return $this->instances->toArray();
    }

}