<?php

namespace Element\Entity\Db;

use Application\Entity\Db\Interfaces\HasNiveauMaitriseInterface;
use Application\Entity\Db\Traits\HasNiveauMaitriseTrait;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;
use UnicaenValidation\Entity\ValidableAwareTrait;
use UnicaenValidation\Entity\ValidableInterface;

class CompetenceElement implements HistoriqueAwareInterface, ValidableInterface, HasNiveauMaitriseInterface {
    use HistoriqueAwareTrait;
    use ValidableAwareTrait;
    use HasNiveauMaitriseTrait;

    /** @var integer */
    private $id;
    /** @var Competence */
    private $competence;
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
     * @return Competence|null
     */
    public function getCompetence(): ?Competence
    {
        return $this->competence;
    }

    /**
     * @param Competence|null $competence
     * @return CompetenceElement
     */
    public function setCompetence(?Competence $competence): CompetenceElement
    {
        $this->competence = $competence;
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
     * @return CompetenceElement
     */
    public function setCommentaire(?string $commentaire): CompetenceElement
    {
        $this->commentaire = $commentaire;
        return $this;
    }

    public function getLibelle()
    {
        return ($this->competence)?$this->competence->getLibelle():"";
    }

    public function getObjet() {
        return $this->getCompetence();
    }
}