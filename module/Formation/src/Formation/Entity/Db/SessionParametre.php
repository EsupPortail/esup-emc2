<?php

namespace Formation\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class SessionParametre implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = -1;
    private ?FormationInstance $session = null;
    private bool $mail = true;
    private bool $evenement = true;
    private bool $enquete = true;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSession(): ?FormationInstance
    {
        return $this->session;
    }

    public function setSession(?FormationInstance $session): void
    {
        $this->session = $session;
    }

    public function isMailActive(): bool
    {
        return $this->mail !== false;
    }

    public function setMail(bool $mail): void
    {
        $this->mail = $mail;
    }

    public function isEvenementActive(): bool
    {
        return $this->evenement;
    }

    public function setEvenement(bool $evenement): void
    {
        $this->evenement = $evenement;
    }

    public function isEnqueteActive(): bool
    {
        return $this->enquete;
    }

    public function setEnquete(bool $enquete): void
    {
        $this->enquete = $enquete;
    }

}