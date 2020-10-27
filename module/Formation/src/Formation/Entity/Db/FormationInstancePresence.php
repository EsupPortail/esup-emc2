<?php

namespace Formation\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class FormationInstancePresence implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var FormationInstanceJournee */
    private $journee;
    /** @var FormationInstanceInscrit */
    private $inscrit;
    /** @var string */
    private $presenceType;
    /** @var bool */
    private $presenceTemoin;
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
     * @return FormationInstanceJournee
     */
    public function getJournee(): FormationInstanceJournee
    {
        return $this->journee;
    }

    /**
     * @param FormationInstanceJournee $journee
     * @return FormationInstancePresence
     */
    public function setJournee(FormationInstanceJournee $journee): FormationInstancePresence
    {
        $this->journee = $journee;
        return $this;
    }

    /**
     * @return FormationInstanceInscrit
     */
    public function getInscrit(): FormationInstanceInscrit
    {
        return $this->inscrit;
    }

    /**
     * @param FormationInstanceInscrit $inscrit
     * @return FormationInstancePresence
     */
    public function setInscrit(FormationInstanceInscrit $inscrit): FormationInstancePresence
    {
        $this->inscrit = $inscrit;
        return $this;
    }

    /**
     * @return string
     */
    public function getPresenceType(): string
    {
        return $this->presenceType;
    }

    /**
     * @param string $presenceType
     * @return FormationInstancePresence
     */
    public function setPresenceType(string $presenceType): FormationInstancePresence
    {
        $this->presenceType = $presenceType;
        return $this;
    }

    /**
     * @return bool
     */
    public function isPresent(): bool
    {
        return $this->presenceTemoin;
    }

    /**
     * @param bool $presenceTemoin
     * @return FormationInstancePresence
     */
    public function setPresent(bool $presenceTemoin): FormationInstancePresence
    {
        $this->presenceTemoin = $presenceTemoin;
        return $this;
    }

    /**
     * @return string
     */
    public function getCommentaire(): string
    {
        return $this->commentaire;
    }

    /**
     * @param string $commentaire
     * @return FormationInstancePresence
     */
    public function setCommentaire(string $commentaire): FormationInstancePresence
    {
        $this->commentaire = $commentaire;
        return $this;
    }

}