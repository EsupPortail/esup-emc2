<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Application\Entity\Db\Traits\HasPeriodeTrait;

class AgentQuotite implements HasPeriodeInterface
{
    use HasPeriodeTrait;
    use DbImportableAwareTrait;

    private ?int $id = null;
    private ?Agent $agent = null;
    private ?int $quotite = null;
    private ?string $modaliteDeService = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getAgent(): Agent
    {
        return $this->agent;
    }

    public function setAgent(Agent $agent): AgentQuotite
    {
        $this->agent = $agent;
        return $this;
    }

    public function getQuotite(): ?int
    {
        return $this->quotite;
    }

    public function setQuotite(?int $quotite): void
    {
        $this->quotite = $quotite;
    }

    public function getModaliteDeService(): ?string
    {
        return $this->modaliteDeService;
    }

    public function setModaliteDeService(?string $modaliteDeService): void
    {
        $this->modaliteDeService = $modaliteDeService;
    }

}