<?php

namespace Formation\Entity\Db;


use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class EnqueteReponse implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const NIVEAUX = [
        5 => "Très satisfait",
        4 => "Assez satisfait",
        3 => "Sans Avis",
        2 => "Peu satisfait",
        1 => "Pas satisfait",

    ];

    private ?int $id = -1;
    protected ?Inscription $inscription = null;
    private ?EnqueteQuestion $question = null;
    private ?int $niveau = null;
    private ?string $description = null;

    public function getId(): int
    {
        return $this->id;
    }

    public function getInscription(): Inscription
    {
        return $this->inscription;
    }

    public function setInscription(Inscription $inscription): void
    {
        $this->inscription = $inscription;
    }

    public function getQuestion(): EnqueteQuestion
    {
        return $this->question;
    }

    public function setQuestion(EnqueteQuestion $question): void
    {
        $this->question = $question;
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(int $niveau): void
    {
        $this->niveau = $niveau;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

}