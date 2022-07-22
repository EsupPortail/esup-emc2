<?php

namespace UnicaenGlossaire\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Definition implements  HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $terme;
    /** @var string */
    private $definition;
    /** @var string */
    private $alternatives;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTerme(): ?string
    {
        return $this->terme;
    }

    /**
     * @param string|null $terme
     * @return Definition
     */
    public function setTerme(?string $terme): Definition
    {
        $this->terme = $terme;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDefinition(): ?string
    {
        return $this->definition;
    }

    /**
     * @param string|null $definition
     * @return Definition
     */
    public function setDefinition(?string $definition): Definition
    {
        $this->definition = $definition;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getAlternatives(): ?string
    {
        return $this->alternatives;
    }

    /**
     * @param string|null $alternatives
     * @return Definition
     */
    public function setAlternatives(?string $alternatives): Definition
    {
        $this->alternatives = $alternatives;
        return $this;
    }

}