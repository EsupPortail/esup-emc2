<?php

namespace Application\Entity\Db;

use Element\Entity\Db\Application;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FicheposteApplicationRetiree implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?FichePoste $fichePoste = null;
    private ?Application $application = null;

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getFichePoste() : ?FichePoste
    {
        return $this->fichePoste;
    }

    public function setFichePoste(?FichePoste $fichePoste) : void
    {
        $this->fichePoste = $fichePoste;
    }

    public function getApplication() : ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application) : void
    {
        $this->application = $application;
    }
}