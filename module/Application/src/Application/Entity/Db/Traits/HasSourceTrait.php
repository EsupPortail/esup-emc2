<?php

namespace Application\Entity\Db\Traits;

trait HasSourceTrait {

    /** @var string */
    private $source;
    /** @var string */
    private $idSource;

    /**
     * @return string|null
     */
    public function getSource() : ?string
    {
        return $this->source;
    }

    /**
     * @return string|null
     */
    public function getIdSource() : ?string
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
     * @param string|null $id
     * @return self
     */
    public function setIdSource(?string $id) : self
    {
        $this->idSource = $id;
        return $this;
    }
}