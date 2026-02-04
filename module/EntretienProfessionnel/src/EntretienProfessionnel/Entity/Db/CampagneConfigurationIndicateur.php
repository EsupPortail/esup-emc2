<?php

namespace EntretienProfessionnel\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class CampagneConfigurationIndicateur implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $code = null;
    private ?string $libelle = null;
    private ?string $requete = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getRequete(): ?string
    {
        return $this->requete;
    }

    public function setRequete(?string $requete): void
    {
        $this->requete = $requete;
    }

    public function prettyprint(): string
    {
        $string = $this->getRequete();
        $string = str_replace('FROM', '<br>FROM', $string);
        $string = str_replace('JOIN', '<br>JOIN', $string);
        $string = str_replace('WHERE', '<br>WHERE', $string);
        return $string;
    }
}
