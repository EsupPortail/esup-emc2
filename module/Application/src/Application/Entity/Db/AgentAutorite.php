<?php

namespace Application\Entity\Db;

use Application\Entity\Db\Interfaces\HasPeriodeInterface;
use Application\Entity\Db\Traits\HasPeriodeTrait;
use DateTime;
use Doctrine\ORM\QueryBuilder;
use RuntimeException;
use UnicaenSynchro\Entity\Db\IsSynchronisableInterface;
use UnicaenSynchro\Entity\Db\IsSynchronisableTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class AgentAutorite implements HistoriqueAwareInterface, IsSynchronisableInterface, HasPeriodeInterface
{
    use HistoriqueAwareTrait;
    use IsSynchronisableTrait;
    use HasPeriodeTrait;

    private ?string $id = null;
    private ?Agent $agent = null;
    private ?Agent $autorite = null;

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
        if ($this->getAgent() === null) { throw new RuntimeException("AgentAutorite::generateId() : Agent manquant");}
        if ($this->getAutorite() === null) { throw new RuntimeException("AgentAutorite::generateId() : Autorite manquant");}
        if ($this->getDateDebut() === null) { throw new RuntimeException("AgentAutorite::generateId() : Date de début manquant");}
        if ($this->sourceId === null) $this->sourceId = 'EMC2';
        $id = $this->sourceId . "-". $this->getAgent()->getId() . "-" . $this->getAutorite()->getId() . "-". $this->getDateDebut()->format('dmYHi') . "-". (new DateTime())->format('YmdHis');
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

    public function getAutorite(): ?Agent
    {
        return $this->autorite;
    }

    public function setAutorite(?Agent $autorite): void
    {
        $this->autorite = $autorite;
    }

    /** decorators ************************************/

    static public function decorateWithAgentAutorite(QueryBuilder $qb, string $entityname = 'agent', bool $histo = false) : QueryBuilder
    {
        $qb = $qb
            //Chaine hierarchique
            ->leftJoin($entityname . '.autorites', 'autorite')
//            ->addSelect('autorite')
            ->leftJoin('autorite.autorite', 'aautorite')
//            ->addSelect('aautorite')
        ;
        if ($histo === false) {
            $qb = $qb->andWhere('autorite.histoDestruction IS NULL');
        }

        return $qb;
    }

}