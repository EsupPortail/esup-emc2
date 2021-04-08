<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasNiveauMaitriseInterface;
use Application\Entity\Db\Traits\HasNiveauMaitriseTrait;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;
use UnicaenValidation\Entity\ValidableAwareTrait;
use UnicaenValidation\Entity\ValidableInterface;

class ApplicationElement implements HistoriqueAwareInterface, ValidableInterface, HasNiveauMaitriseInterface {
    use HistoriqueAwareTrait;
    use ValidableAwareTrait;
    use HasNiveauMaitriseTrait;

    /** @var integer */
    private $id;
    /** @var Application */
    private $application;
    /** @var string */
    private $commentaire;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Application|null
     */
    public function getApplication(): ?Application
    {
        return $this->application;
    }

    /**
     * @param Application|null $application
     * @return ApplicationElement
     */
    public function setApplication(?Application $application): ApplicationElement
    {
        $this->application = $application;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    /**
     * @param string|null $commentaire
     * @return ApplicationElement
     */
    public function setCommentaire(?string $commentaire): ApplicationElement
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getLibelle()
    {
        return ($this->application)?$this->application->getLibelle():"";
    }

    public function getObjet() {
        return $this->getApplication();
    }
}