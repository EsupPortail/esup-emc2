<?php

namespace FicheMetier\Entity\Db;


use Element\Entity\Db\Application;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FicheMetierConfigurationApplicationParDefaut implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?Application $application = null;

    /** Remarque : on pourrait aussi ajouter un niveau de maitrise (on reproduit à l'identique pour le moment) **/

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application): void
    {
        $this->application = $application;
    }


}