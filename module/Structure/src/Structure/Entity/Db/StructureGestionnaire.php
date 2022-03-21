<?php

namespace Structure\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\DbImportableAwareTrait;
use Application\Entity\Db\Traits\HasPeriodeTrait;

class StructureGestionnaire implements HasPeriodeInterface {
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
     * @return StructureGestionnaire
     */
    public function setStructure(Structure $structure): StructureGestionnaire
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
     * @return StructureGestionnaire
     */
    public function setAgent(Agent $agent): StructureGestionnaire
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
     * @return StructureGestionnaire
     */
    public function setFonctionId(?int $fonctionId): StructureGestionnaire
    {
        $this->fonctionId = $fonctionId;
        return $this;
    }

    /** @var bool */
    private $imported;

    public function isImported() : bool
    {
        return ($this->imported === true);
    }

    /**
     * @param bool $imported
     * @return DbImportableAwareTrait
     */
    public function setImported(bool $imported)
    {
        $this->imported = $imported;
        return $this;
    }
}