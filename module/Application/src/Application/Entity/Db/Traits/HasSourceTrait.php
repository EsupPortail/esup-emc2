<?php

namespace Application\Entity\Db\Traits;

trait HasSourceTrait {

    /** @var string */
    private $source;
    /** @var int */
    private $idSource;

    /**
     * @return string|null
     */
    public function getSource() : ?string
    {
        return $this->source;
    }

    /**
     * @return int|null
     */
    public function getIdSource() : ?int
    {
        return $this->idSource;
    }

    /**
     * @param string|null $source
     * @return self
     */
    public function setSource(?string $source) : self
    {
        $this->source = $source;
        return $this;
    }

    /**
     * @param int|null $id
     * @return self
     */
    public function setIdSource(?int $id) : self
    {
        $this->idSource = $id;
        return $this;
    }
}