<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Formation\Entity\Db\Formation;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class ParcoursDeFormation implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const TYPE_CATEGORIE = 'Catégorie';
    const TYPE_METIER    = 'Métier';

    /** @var integer */
    private $id;
    /** @var string */
    private $type;
    /** @var integer */
    private $reference;
    /** @var string */
    private $libelle;
    /** @var string */
    private $description;
    /** @var ArrayCollection (Formation) */
    private $formations;

    public function __construct()
    {
        $this->formations = new ArrayCollection();
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
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return ParcoursDeFormation
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return int
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param int $reference
     * @return ParcoursDeFormation
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
        return $this;
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
     * @return ParcoursDeFormation
     */
    public function setLibelle($libelle)
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
     * @return ParcoursDeFormation
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return ParcoursDeFormationFormation[]
     */
    public function getFormations()
    {
        if ($this->formations === null) return [];
        return $this->formations->toArray();
    }

    /**
     * @param Formation $formation
     * @return boolean
     */
    public function hasFormation(Formation $formation)
    {
        return $this->formations->contains($formation);
    }

    /**
     * @param Formation $formation
     * @return ParcoursDeFormation
     */
    public function addFormation(Formation $formation)
    {
        if (! $this->formations->contains($formation)) $this->formations->add($formation);
        return $this;
    }

    /**
     * @param Formation $formation
     * @return ParcoursDeFormation
     */
    public function removeFormation(Formation $formation)
    {
        $this->formations->removeElement($formation);
        return $this;
    }

    /**
     * @param Formation[] $formations
     * @return ParcoursDeFormation
     */
    public function setFormations($formations)
    {
        $this->formations->clear();
        foreach($formations as $formation) {
            $this->formations->add($formation);
        }
        return $this;
    }
}