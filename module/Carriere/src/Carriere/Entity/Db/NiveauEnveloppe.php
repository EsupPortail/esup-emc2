<?php

namespace Carriere\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Interfaces\HasDescriptionInterface;
use Application\Entity\Db\Traits\HasDescriptionTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class NiveauEnveloppe implements HistoriqueAwareInterface, HasDescriptionInterface {
    use HistoriqueAwareTrait;
    use HasDescriptionTrait;

    private ?int $id = null;
    private ?Niveau $borneInferieure = null;
    private ?Niveau $borneSuperieure = null;
    private ?niveau $valeurRecommandee = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBorneInferieure(): ?Niveau
    {
        return $this->borneInferieure;
    }

    public function setBorneInferieure(Niveau $borneInferieure): void
    {
        $this->borneInferieure = $borneInferieure;
    }

    public function getBorneSuperieure(): ?Niveau
    {
        return $this->borneSuperieure;
    }

    public function setBorneSuperieure(Niveau $borneSuperieure): void
    {
        $this->borneSuperieure = $borneSuperieure;
    }

    public function getValeurRecommandee(): ?Niveau
    {
        return $this->valeurRecommandee;
    }

    public function setValeurRecommandee(?Niveau $valeurRecommandee): void
    {
        $this->valeurRecommandee = $valeurRecommandee;
    }

    public function getDeltaWithAgent(?Agent $agent = null) : ?int
    {
        if ($agent === null) { return null; }
        $niveauxAgent = $agent->getNiveauEnveloppe();
        if ($niveauxAgent === null) return null;
        $niveauSup = $this->getBorneSuperieure()->getNiveau();
        $niveauInf = $this->getBorneInferieure()->getNiveau();

        if ($niveauxAgent->getBorneSuperieure()->getNiveau() >= $niveauSup AND $niveauxAgent->getBorneInferieure()->getNiveau() <= $niveauInf) return 0;
        return max (($niveauSup - $niveauxAgent->getBorneSuperieure()->getNiveau()), ($niveauxAgent->getBorneInferieure()->getNiveau() - $niveauInf));
    }

    static public function isCompatible(?NiveauEnveloppe $niveauA, ?NiveauEnveloppe $niveauB) : bool
    {
        if ($niveauA === null or $niveauB === null) return true; //todo statuer
        if ($niveauA->getBorneInferieure()->getNiveau() < $niveauB->getBorneSuperieure()->getNiveau()) return false;
        if ($niveauA->getBorneSuperieure()->getNiveau() > $niveauB->getBorneInferieure()->getNiveau()) return false;
        return true;
    }
}