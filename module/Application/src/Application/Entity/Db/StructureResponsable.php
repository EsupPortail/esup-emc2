<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Application\Entity\Db\Traits\HasPeriodeTrait;

class StructureResponsable implements HasPeriodeInterface {
    use DbImportableAwareTrait;
    use HasPeriodeTrait;

    /** @var int */
    private $id;
    /** @var Structure */
    private $structure;
    /** @var Agent */
    private $agent;
    /** @var int|null */
    private $fonctionId;

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Structure
     */
    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    /**
     * @param Structure $structure
     * @return StructureResponsable
     */
    public function setStructure(Structure $structure): StructureResponsable
    {
        $this->structure = $structure;
        return $this;
    }

    /**
     * @return Agent
     */
    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    /**
     * @param Agent $agent
     * @return StructureResponsable
     */
    public function setAgent(Agent $agent): StructureResponsable
    {
        $this->agent = $agent;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getFonctionId(): ?int
    {
        return $this->fonctionId;
    }

    /**
     * @param int|null $fonctionId
     * @return StructureResponsable
     */
    public function setFonctionId(?int $fonctionId): StructureResponsable
    {
        $this->fonctionId = $fonctionId;
        return $this;
    }


}