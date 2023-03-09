<?php

namespace Application\Entity\Db\Traits;


trait HasSourceTrait {

    private ?string $source = null;
    private ?string $idSource = null;

    public function getSource() : ?string
    {
        return $this->source;
    }

    public function getIdSource() : ?string
    {
        return $this->idSource;
    }

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