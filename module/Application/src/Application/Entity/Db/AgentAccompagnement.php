<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use Carriere\Entity\Db\Corps;
use Carriere\Entity\Db\Correspondance;
use UnicaenEtat\src\UnicaenEtat\Entity\Db\Etat;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class AgentAccompagnement implements HasPeriodeInterface, HistoriqueAwareInterface {
    use HasPeriodeTrait;
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var Agent|null */
    private $cible;
    /** @var Correspondance|null */
    private $bap;
    /** @var Corps|null */
    private $corps;
    /** @var string|null */
    private $complement;
    /** @var bool|null */
    private $resultat;
    /** @var UnicaenEtat\src\UnicaenEtat\Entity\Db\Etat|null */
    private $etat;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return AgentAccompagnement
     */
    public function setId(int $id): AgentAccompagnement
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return Agent
     */
    public function getAgent(): Agent
    {
        return $this->agent;
    }

    /**
     * @param Agent $agent
     * @return AgentAccompagnement
     */
    public function setAgent(Agent $agent): AgentAccompagnement
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return Agent|null
     */
    public function getCible(): ?Agent
    {
        return $this->cible;
    }

    /**
     * @param Agent|null $cible
     * @return AgentAccompagnement
     */
    public function setCible(?Agent $cible): AgentAccompagnement
    {
        $this->cible = $cible;
        return $this;
    }

    /**
     * @return Correspondance|null
     */
    public function getBap(): ?Correspondance
    {
        return $this->bap;
    }

    /**
     * @param Correspondance|null $bap
     * @return AgentAccompagnement
     */
    public function setBap(?Correspondance $bap): AgentAccompagnement
    {
        $this->bap = $bap;
        return $this;
    }

    /**
     * @return Corps|null
     */
    public function getCorps(): ?Corps
    {
        return $this->corps;
    }

    /**
     * @param Corps|null $corps
     * @return AgentAccompagnement
     */
    public function setCorps(?Corps $corps): AgentAccompagnement
    {
        $this->corps = $corps;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getComplement(): ?string
    {
        return $this->complement;
    }

    /**
     * @param string|null $complement
     * @return AgentAccompagnement
     */
    public function setComplement(?string $complement): AgentAccompagnement
    {
        $this->complement = $complement;
        return $this;
    }

    /**
     * @return bool|null
     */
    public function getResultat(): ?bool
    {
        return $this->resultat;
    }

    /**
     * @param bool|null $resultat
     * @return AgentAccompagnement
     */
    public function setResultat(?bool $resultat): AgentAccompagnement
    {
        $this->resultat = $resultat;
        return $this;
    }

    /**
     * @return UnicaenEtat\src\UnicaenEtat\Entity\Db\Etat|null
     */
    public function getEtat(): ?UnicaenEtat\src\UnicaenEtat\Entity\Db\Etat
    {
        return $this->etat;
    }

    /**
     * @param UnicaenEtat\src\UnicaenEtat\Entity\Db\Etat|null $etat
     * @return AgentAccompagnement
     */
    public function setEtat(?UnicaenEtat\src\UnicaenEtat\Entity\Db\Etat $etat): AgentAccompagnement
    {
        $this->etat = $etat;
        return $this;
    }

}