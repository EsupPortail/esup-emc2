<?php

namespace Autoform\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class FormulaireReponse implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var FormulaireInstance */
    private $instance;
    /** @var Champ */
    private $champ;
    /** @var string */
    private $reponse;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * @return FormulaireReponse
     */
    public function setFormulaireInstance($instance)
    {
        $this->instance = $instance;
        return $this;
    }

    /**
     * @return Champ
     */
    public function getChamp() : ?Champ
    {
        return $this->champ;
    }

    /**
     * @param Champ $champ
     * @return FormulaireReponse
     */
    public function setChamp($champ)
    {
        $this->champ = $champ;
        return $this;
    }

    /**
     * @return string
     */
    public function getReponse()
    {
        return $this->reponse;
    }

    /**
     * @param string $reponse
     * @return FormulaireReponse
     */
    public function setReponse($reponse)
    {
        $this->reponse = $reponse;
        return $this;
    }


}