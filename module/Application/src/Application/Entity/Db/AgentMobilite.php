<?php

namespace Application\Entity\Db;

use Carriere\Entity\Db\Mobilite;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;

class AgentMobilite implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?Agent $agent = null;
    private ?Mobilite $mobilite = null;
    private ?string $commentaire = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getAgent(): ?Agent
    {
        return $this->agent;
    }

    public function setAgent(?Agent $agent): AgentMobilite
    {
        $this->agent = $agent;
        return $this;
    }

    public function getMobilite(): ?Mobilite
    {
        return $this->mobilite;
    }

    public function setMobilite(?Mobilite $mobilite): void
    {
        $this->mobilite = $mobilite;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): void
    {
        $this->commentaire = $commentaire;
    }
}