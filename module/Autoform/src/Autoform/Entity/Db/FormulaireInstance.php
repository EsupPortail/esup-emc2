<?php

namespace Autoform\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FormulaireInstance {
    use HistoriqueAwareTrait;

    /** @var integer */
    private  $id;
    /** @var Formulaire */
    private  $formulaire;


    /** @var ArrayCollection */
    private $reponses;
    /** @var ArrayCollection */
    private $validations;

    public function __construct()
    {
        $this->reponses = new ArrayCollection();
        $this->validations = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return FormulaireInstance
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Formulaire
     */
    public function getFormulaire()
    {
        return $this->formulaire;
    }

    /**
     * @param Formulaire $formulaire
     * @return FormulaireInstance
     */
    public function setFormulaire($formulaire)
    {
        $this->formulaire = $formulaire;
        return $this;
    }

    /**
     * @return FormulaireReponse[]
     */
    public function getReponses()
    {
        return $this->reponses->toArray();
    }

    /**
     * @param FormulaireReponse $reponse
     * @return FormulaireInstance
     */
    public function addReponse($reponse)
    {
        $this->reponses->add($reponse);
        return $this;
    }

    /**
     * @param FormulaireReponse $reponse
     * @return FormulaireInstance
     */
    public function removeReponse($reponse)
    {
        $this->reponses->removeElement($reponse);
        return $this;
    }

    /**
     * @return Validation[]
     */
    public function getValidations()
    {
        return $this->validations->toArray();
    }

    /**
     * @param string $type
     * @return Validation
     */
    public function getValidationByType($type)
    {
        foreach($this->validations as $validation) {
            if ($validation->getHistoDestruction() === null && $validation->getType() === $type) return $validation;
        }
        return null;
    }

    /**
     * @param Validation $validation
     * @return FormulaireInstance
     */
    public function addValidation($validation)
    {
        $this->validations->add($validation);
        return $this;
    }

    /**
     * @param Validation $validation
     * @return FormulaireInstance
     */
    public function removeValidation($validation)
    {
        $this->validations->removeElement($validation);
        return $this;
    }

    /**
     * @param integer $to
     * @return Champ
     */
    public function getChamp($to)
    {
        foreach ($this->getFormulaire()->getCategories() as $categorie) {
            foreach ($categorie->getChamps() as $champ) {
                if ($champ->getId() === $to) return $champ;
            }
        }
        return null;
    }

}