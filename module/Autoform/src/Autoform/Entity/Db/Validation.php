<?php

namespace Autoform\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareTrait;

class Validation {
    use HistoriqueAwareTrait;

    const TYPE_SUMPPS       = "SUMPPS";
    const TYPE_MEDECIN      = "MEDECIN";
    const TYPE_COMPOSANTE   = "COMPOSANTE";

    /** @var integer */
    private $id;
    /** @var string */
    private $type;
    /** @var FormulaireInstance */
    private $instance;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     * @return Validation
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return FormulaireInstance
     */
    public function getFormulaireInstance()
    {
        return $this->instance;
    }

    /**
     * @param FormulaireInstance $instance
     * @return Validation
     */
    public function setFormulaireInstance($instance)
    {
        $this->instance = $instance;
        return $this;
    }



}