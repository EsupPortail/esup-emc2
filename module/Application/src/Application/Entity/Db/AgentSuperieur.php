<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Traits\HasPeriodeTrait;
use Doctrine\ORM\QueryBuilder;
use RuntimeException;
use UnicaenSynchro\Entity\Db\IsSynchronisableInterface;
use UnicaenSynchro\Entity\Db\IsSynchronisableTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class AgentSuperieur implements HistoriqueAwareInterface, IsSynchronisableInterface
{
    use HistoriqueAwareTrait;
    use IsSynchronisableTrait;
    use HasPeriodeTrait;

    private ?string $id = null;
    private ?Agent $agent = null;
    private ?Agent $superieur = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function generateId(): ?string
    {
        if ($this->getAgent() === null) { throw new RuntimeException("AgentSupeieur::generateId() : Agent manquant");}
        if ($this->getSuperieur() === null) { throw new RuntimeException("AgentSupeieur::generateId() : Superieur manquant");}
        if ($this->getDateDebut() === null) { throw new RuntimeException("AgentSupeieur::generateId() : Date de début manquant");}
        if ($this->sourceId === null) $this->sourceId = 'EMC2';
        $id = $this->sourceId . "-". $this->getAgent()->getId() . "-" . $this->getSuperieur()->getId() . "-". $this->getDateDebut()->format('dmYHi');
        return $id;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): void
    {
        $this->agent = $agent;
    }

    public function getSuperieur(): ?Agent
    {
        return $this->superieur;
    }

    public function setSuperieur(?Agent $superieur): void
    {
        $this->superieur = $superieur;
    }

    /** decorators ************************************/

    static public function decorateWithAgentSuperieur(QueryBuilder $qb, string $entityname = 'agent', bool $histo = false) : QueryBuilder
    {
        $qb = $qb
            //Chaine hierarchique
            ->leftJoin($entityname .'.superieurs', 'superieur')
//            ->addSelect('superieur')
            ->leftJoin('superieur.superieur', 'asuperieur')
//            ->addSelect('asuperieur')
        ;
        if ($histo === false) {
            $qb = $qb->andWhere('superieur.histoDestruction IS NULL');
        }

        return $qb;
    }
}