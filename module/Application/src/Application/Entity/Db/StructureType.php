<?php

namespace Application\Entity\Db;

use Application\Entity\SynchroAwareInterface;
use Application\Entity\SynchroAwareTrait;

class StructureType implements SynchroAwareInterface {
    use SynchroAwareTrait;

    /** @var integer */
    private $id;
    /** @var integer */
    private $source_id;
    /** @var string */
    private $code;
    /** @var string */
    private $libelle;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return StructureType
     */
    public function setId(int $id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getSourceId()
    {
        return $this->source_id;
    }

    /**
     * @param int $source_id
     * @return StructureType
     */
    public function setSourceId(int $source_id)
    {
        $this->source_id = $source_id;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return StructureType
     */
    public function setCode(string $code)
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return StructureType
     */
    public function setLibelle(string $libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

}