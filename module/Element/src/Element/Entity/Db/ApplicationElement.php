<?php

namespace Element\Entity\Db;

use Element\Entity\Db\Interfaces\HasNiveauInterface;
use Element\Entity\Db\Traits\HasNiveauTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenValidation\Entity\ValidableAwareTrait;
use UnicaenValidation\Entity\ValidableInterface;

class ApplicationElement implements HistoriqueAwareInterface, ValidableInterface, HasNiveauInterface {
    use HistoriqueAwareTrait;
    use ValidableAwareTrait;
    use HasNiveauTrait;

    private ?int $id = null;
    private ?Application $application = null;
    private ?string $commentaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application): void
    {
        $this->application = $application;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): void
    {
        $this->commentaire = $commentaire;
    }

    public function getLibelle() : ?string
    {
        return ($this->application)?$this->application->getLibelle():"";
    }

    public function getObjet() : ?Application
    {
        return $this->getApplication();
    }
}