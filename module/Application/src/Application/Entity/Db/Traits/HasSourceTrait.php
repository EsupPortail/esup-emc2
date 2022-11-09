<?php

namespace Application\Entity\Db\Traits;

use UnicaenDbImport\Entity\Db\Source;

trait HasSourceTrait {

    private ?Source $source = null;
    private ?string $idSource = null;

    /**
     * @return Source|null
     */
    public function getSource() : ?Source
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
     * @param Source|null $source
     * @return self
     */
    public function setSource(?Source $source) : self
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