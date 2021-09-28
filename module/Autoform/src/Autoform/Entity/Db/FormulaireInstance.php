<?php

namespace Autoform\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FormulaireInstance implements HistoriqueAwareInterface {
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

    /**
     * @param Champ $champ
     * @return string|null
     */
    public function getReponseFor(Champ $champ) : ?string
    {
        /** @var FormulaireReponse $reponse */
        foreach ($this->reponses as $reponse) {
            if ($reponse->estNonHistorise() AND $reponse->getChamp()->getId() === $champ->getId())
                return $reponse->getReponse();
        }
        return null;
    }

    public function prettyPrint() : string
    {
        $text = "";
        $categories = $this->getFormulaire()->getCategories();
        $categories = array_filter($categories, function (Categorie $a) { return $a->estNonHistorise(); });
        usort($categories, function (Categorie $a, Categorie $b) { return $a->getOrdre() > $b->getOrdre(); });

        foreach ($categories as $categorie) {
            $as = false;
            $subtext = "<h3>".$categorie->getLibelle()."</h3>";

            $champs = $categorie->getChamps();
            $champs = array_filter($champs, function (Champ $a) { return $a->estNonHistorise(); });
            usort($champs, function (Champ $a, Champ $b) { return $a->getOrdre() > $b->getOrdre(); });

            foreach ($champs as $champ) {
                $reponse = $this->getReponseFor($champ);
                //todo utiliser les VHs
                if ($reponse !== null) {
                    $subtext .= $champ->getLibelle() . " : ". $reponse . "<br>";
                    $as = true;
                }
            }

            if ($as) $text .= $subtext;
        }
        return $text;
    }

}