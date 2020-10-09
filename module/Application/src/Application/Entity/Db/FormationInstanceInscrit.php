<?php

namespace Application\Entity\Db;

use Application\Entity\HasAgentInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FormationInstanceInscrit implements HistoriqueAwareInterface, HasAgentInterface {
    use HistoriqueAwareTrait;

    const PRINCIPALE = 'principale';
    const COMPLEMENTAIRE = 'complementaire';

    /** @var integer */
    private $id;
    /** @var FormationInstance */
    private $instance;
    /** @var Agent */
    private $agent;
    /** @var string */
    private $liste;
    /** @var FormationInstanceFrais */
    private $frais;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FormationInstance
     */
    public function getInstance()
    {
        return $this->instance;
    }

    /**
     * @param FormationInstance $instance
     * @return FormationInstanceInscrit
     */
    public function setInstance($instance)
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * @return Agent
     */
    public function getAgent()
    {
        return $this->agent;
    }

    /**
     * @param Agent $agent
     * @return FormationInstanceInscrit
     */
    public function setAgent($agent)
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return string
     */
    public function getListe()
    {
        return $this->liste;
    }

    /**
     * @param string $liste
     * @return FormationInstanceInscrit
     */
    public function setListe($liste)
    {
        $this->liste = $liste;
        return $this;
    }


    /**
     * @return FormationInstanceFrais|null
     */
    public function getFrais(): ?FormationInstanceFrais
    {
        return $this->frais;
    }

    /**
     * @param FormationInstanceFrais|null $frais
     * @return FormationInstanceInscrit
     */
    public function setFrais(?FormationInstanceFrais $frais): FormationInstanceInscrit
    {
        $this->frais = $frais;
        return $this;
    }


}