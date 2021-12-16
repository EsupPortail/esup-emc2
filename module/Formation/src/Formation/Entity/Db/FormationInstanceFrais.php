<?php

namespace Formation\Entity\Db;

use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Entity\Db\Traits\HasSourceTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class FormationInstanceFrais implements HistoriqueAwareInterface, HasSourceInterface
{
    use HistoriqueAwareTrait;
    use HasSourceTrait;

    /** @var integer */
    private $id;
    /** @var FormationInstanceInscrit */
    private $inscrit;
    /** @var float */
    private $fraisRepas;
    /** @var float */
    private $fraisHebergement;
    /** @var float */
    private $fraisTransport;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return FormationInstanceInscrit
     */
    public function getInscrit(): FormationInstanceInscrit
    {
        return $this->inscrit;
    }

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstanceFrais
     */
    public function setInscrit(FormationInstanceInscrit $inscrit): FormationInstanceFrais
    {
        $this->inscrit = $inscrit;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getFraisRepas(): ?float
    {
        return $this->fraisRepas;
    }

    /**
     * @param float $fraisRepas
     * @return FormationInstanceFrais
     */
    public function setFraisRepas(float $fraisRepas): FormationInstanceFrais
    {
        $this->fraisRepas = $fraisRepas;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getFraisHebergement(): ?float
    {
        return $this->fraisHebergement;
    }

    /**
     * @param float $fraisHebergement
     * @return FormationInstanceFrais
     */
    public function setFraisHebergement(float $fraisHebergement): FormationInstanceFrais
    {
        $this->fraisHebergement = $fraisHebergement;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getFraisTransport(): ?float
    {
        return $this->fraisTransport;
    }

    /**
     * @param float $fraisTransport
     * @return FormationInstanceFrais
     */
    public function setFraisTransport(float $fraisTransport): FormationInstanceFrais
    {
        $this->fraisTransport = $fraisTransport;
        return $this;
    }

    /**
     * @return string
     */
    public function afficheFrais()
    {
        $text = "<strong> Repas </strong> : " . (($this->getFraisRepas()) ?: "0.00") . " € <br/>";
        $text .= "<strong> Hébergement </strong> : " . (($this->getFraisHebergement()) ?: "0.00") . " € <br/>";
        $text .= "<strong> Transport </strong> : " . (($this->getFraisTransport()) ?: "0.00") . " € ";
        return $text;
    }

}