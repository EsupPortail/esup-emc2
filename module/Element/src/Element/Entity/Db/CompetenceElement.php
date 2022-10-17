<?php

namespace Element\Entity\Db;

use Element\Entity\Db\Interfaces\HasNiveauInterface;
use Element\Entity\Db\Traits\HasNiveauTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenValidation\Entity\ValidableAwareTrait;
use UnicaenValidation\Entity\ValidableInterface;

class CompetenceElement implements HistoriqueAwareInterface, ValidableInterface, HasNiveauInterface {
    use HistoriqueAwareTrait;
    use ValidableAwareTrait;
    use HasNiveauTrait;

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