<?php

namespace UnicaenDocument\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Macro implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var string */
    private $variable;
    /** @var string */
    private $description;
    /** @var string */
    private $methode;

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode() : ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     * @return Macro
     */
    public function setCode(?string $code) : Macro
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getVariable()
    {
        return $this->variable;
    }

    /**
     * @param string $variable
     * @return Macro
     */
    public function setVariable($variable) : Macro
    {
        $this->variable = $variable;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription() : ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return Macro
     */
    public function setDescription(?string $description) : Macro
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @return string
     */
    public function getMethode() : ?string
    {
        return $this->methode;
    }

    /**
     * @param string|null $methode
     * @return Macro
     */
    public function setMethode(?string $methode) : Macro
    {
        $this->methode = $methode;
        return $this;
    }



}