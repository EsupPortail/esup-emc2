<?php

namespace Application\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class ActiviteApplication {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var Activite */
    private $activite;
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
     * @return Activite
     */
    public function getActivite()
    {
        return $this->activite;
    }

    /**
     * @param Activite $activite
     * @return ActiviteApplication
     */
    public function setActivite($activite)
    {
        $this->activite = $activite;
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
     * @return ActiviteApplication
     */
    public function setApplication($application)
    {
        $this->application = $application;
        return $this;
    }
}
