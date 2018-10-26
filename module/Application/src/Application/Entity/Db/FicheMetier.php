<?php

namespace Application\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareTrait;

class FicheMetier
{
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var string */
    private $libelle;

    public function getId()
    {
        return $this->id;
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
     * @return FicheMetier
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }


}