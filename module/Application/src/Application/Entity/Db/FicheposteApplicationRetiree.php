<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FicheposteApplicationRetiree implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var FichePoste */
    private $fichePoste;
    /** @var Application */
    private $application;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return FichePoste
     */
    public function getFichePoste()
    {
        return $this->fichePoste;
    }

    /**
     * @param FichePoste $fichePoste
     * @return FicheposteApplicationRetiree
     */
    public function setFichePoste($fichePoste)
    {
        $this->fichePoste = $fichePoste;
        return $this;
    }


    /**
     * @return Application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * @param Application $application
     * @return FicheposteApplicationRetiree
     */
    public function setApplication($application)
    {
        $this->application = $application;
        return $this;
    }
}