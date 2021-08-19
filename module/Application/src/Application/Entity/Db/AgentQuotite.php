<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\DbImportableAwareTrait;
use DateTime;
use UnicaenUtilisateur\Entity\DateTimeAwareTrait;

/**
 * Données synchronisées depuis Octopus :
 * - pas de setter sur les données ainsi remontées
 */
class AgentQuotite
{
    use DbImportableAwareTrait;
    use DateTimeAwareTrait;

    /** @var int */
    private $id;
    /** @var Agent */
    private $agent;
    /** @var DateTime */
    private $debut;
    /** @var DateTime */
    private $fin;
    /** @var integer */
    private $quotite;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
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
     * @return AgentQuotite
     */
    public function setAgent(Agent $agent): AgentQuotite
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getDebut(): ?DateTime
    {
        return $this->debut;
    }

    /**
     * @param DateTime|null $debut
     * @return AgentQuotite
     */
    public function setDebut(?DateTime $debut): AgentQuotite
    {
        $this->debut = $debut;
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getFin(): ?DateTime
    {
        return $this->fin;
    }

    /**
     * @param DateTime|null $fin
     * @return AgentQuotite
     */
    public function setFin(?DateTime $fin): AgentQuotite
    {
        $this->fin = $fin;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getQuotite(): ?int
    {
        return $this->quotite;
    }

    /**
     * @param int $quotite
     * @return AgentQuotite
     */
    public function setQuotite(int $quotite): AgentQuotite
    {
        $this->quotite = $quotite;
        return $this;
    }

    public function isEnCours(?DateTime $date = null)
    {
        $date = $date ? : $this->getDateTime();
        if ($date < $this->getDebut()) return false;
        if ($this->getFin() !== null and $date > $this->getFin()) return false;
        return true;
    }
}