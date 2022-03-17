<?php

namespace Element\Entity\Db;

use Element\Entity\Db\Interfaces\HasNiveauInterface;
use Element\Entity\Db\Traits\HasNiveauTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenValidation\Entity\ValidableAwareTrait;
use UnicaenValidation\Entity\ValidableInterface;

class ApplicationElement implements HistoriqueAwareInterface, ValidableInterface, HasNiveauInterface {
    use HistoriqueAwareTrait;
    use ValidableAwareTrait;
    use HasNiveauTrait;

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