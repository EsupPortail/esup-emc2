<?php

namespace EntretienProfessionnel\Entity\Db;

use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

/**
 * Cette entité est utilisée pour alimenter le formulaire d'entretien pro
 */
class CriterePersonnelle implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private int $id = -1;
    private ?string $libelle = null;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

}