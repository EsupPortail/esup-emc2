<?php

namespace Application\Entity\Db;

use Doctrine\ORM\QueryBuilder;
use UnicaenSynchro\Entity\Db\IsSynchronisableInterface;
use UnicaenSynchro\Entity\Db\IsSynchronisableTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class AgentSuperieur implements HistoriqueAwareInterface, IsSynchronisableInterface
{
    use HistoriqueAwareTrait;
    use IsSynchronisableTrait;

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