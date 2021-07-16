<?php

namespace Metier\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class MetierNiveau implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var Metier */
    private $metier;
    /** @var int */
    private $borneInferieure;
    /** @var int */
    private $borneSuperieure;
    /** @var int */
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
     * @return Metier|null
     */
    public function getMetier(): ?Metier
    {
        return $this->metier;
    }

    /**
     * @param Metier $metier
     * @return MetierNiveau
     */
    public function setMetier(Metier $metier): MetierNiveau
    {
        $this->metier = $metier;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getBorneInferieure(): ?int
    {
        return $this->borneInferieure;
    }

    /**
     * @param int $borneInferieure
     * @return MetierNiveau
     */
    public function setBorneInferieure(int $borneInferieure): MetierNiveau
    {
        $this->borneInferieure = $borneInferieure;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getBorneSuperieure(): ?int
    {
        return $this->borneSuperieure;
    }

    /**
     * @param int $borneSuperieure
     * @return MetierNiveau
     */
    public function setBorneSuperieure(int $borneSuperieure): MetierNiveau
    {
        $this->borneSuperieure = $borneSuperieure;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getValeurRecommandee(): ?int
    {
        return $this->valeurRecommandee;
    }

    /**
     * @param int $valeurRecommandee
     * @return MetierNiveau
     */
    public function setValeurRecommandee(int $valeurRecommandee): MetierNiveau
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
     * @return MetierNiveau
     */
    public function setDescription(?string $description): MetierNiveau
    {
        $this->description = $description;
        return $this;
    }
}