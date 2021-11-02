<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Application\Entity\Db\Traits\HasPeriodeTrait;

class AgentQuotite implements HasPeriodeInterface
{
    use HasPeriodeTrait;
    use DbImportableAwareTrait;

    /** @var int */
    private $id;
    /** @var Agent */
    private $agent;
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

}