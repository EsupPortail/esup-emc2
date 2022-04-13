<?php

namespace Carriere\Entity\Db;

use Application\Entity\Db\Agent;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class NiveauEnveloppe implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var Niveau */
    private $borneInferieure;
    /** @var Niveau */
    private $borneSuperieure;
    /** @var Niveau|null */
    private $valeurRecommandee;
    /** @var string|null */
    private $description;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Niveau|null
     */
    public function getBorneInferieure(): ?Niveau
    {
        return $this->borneInferieure;
    }

    /**
     * @param Niveau $borneInferieure
     * @return NiveauEnveloppe
     */
    public function setBorneInferieure(Niveau $borneInferieure): NiveauEnveloppe
    {
        $this->borneInferieure = $borneInferieure;
        return $this;
    }

    /**
     * @return Niveau|null
     */
    public function getBorneSuperieure(): ?Niveau
    {
        return $this->borneSuperieure;
    }

    /**
     * @param Niveau $borneSuperieure
     * @return NiveauEnveloppe
     */
    public function setBorneSuperieure(Niveau $borneSuperieure): NiveauEnveloppe
    {
        $this->borneSuperieure = $borneSuperieure;
        return $this;
    }

    /**
     * @return Niveau|null
     */
    public function getValeurRecommandee(): ?Niveau
    {
        return $this->valeurRecommandee;
    }

    /**
     * @param Niveau|null $valeurRecommandee
     * @return NiveauEnveloppe
     */
    public function setValeurRecommandee(?Niveau $valeurRecommandee): NiveauEnveloppe
    {
        $this->valeurRecommandee = $valeurRecommandee;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     * @return NiveauEnveloppe
     */
    public function setDescription(?string $description): NiveauEnveloppe
    {
        $this->description = $description;
        return $this;
    }

    /**
     * @param Agent|null $agent
     * @return int|null
     */
    public function getDeltaWithAgent(?Agent $agent = null) : ?int
    {
        if ($agent === null) { return null; }
        $niveauxAgent = $agent->getNiveauEnveloppe();
        $niveauSup = $this->getBorneSuperieure()->getNiveau();
        $niveauInf = $this->getBorneInferieure()->getNiveau();

        if ($niveauxAgent->getBorneSuperieure()->getNiveau() >= $niveauSup AND $niveauxAgent->getBorneInferieure()->getNiveau() <= $niveauInf) return 0;
        return max (($niveauSup - $niveauxAgent->getBorneSuperieure()->getNiveau()), ($niveauxAgent->getBorneInferieure()->getNiveau() - $niveauInf));
    }

    /**
     * @param NiveauEnveloppe $niveauA
     * @param NiveauEnveloppe $niveauB
     * @return bool
     */
    static public function isCompatible(NiveauEnveloppe $niveauA, NiveauEnveloppe $niveauB) : bool
    {
        if ($niveauA->getBorneInferieure()->getNiveau() < $niveauB->getBorneSuperieure()->getNiveau()) return false;
        if ($niveauA->getBorneSuperieure()->getNiveau() > $niveauB->getBorneInferieure()->getNiveau()) return false;
        return true;
    }
}