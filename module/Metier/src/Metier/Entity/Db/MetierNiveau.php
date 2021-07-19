<?php

namespace Metier\Entity\Db;

use Application\Entity\Db\Niveau;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class MetierNiveau implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var Metier */
    private $metier;
    /** @var Niveau */
    private $borneInferieure;
    /** @var Niveau */
    private $borneSuperieure;
    /** @var Niveau */
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
     * @return Niveau|null
     */
    public function getBorneInferieure(): ?Niveau
    {
        return $this->borneInferieure;
    }

    /**
     * @param Niveau $borneInferieure
     * @return MetierNiveau
     */
    public function setBorneInferieure(Niveau $borneInferieure): MetierNiveau
    {
        $this->borneInferieure = $borneInferieure;
        return $this;
    }

    /**
     * @return Niveau|null
     */
    public function getBorneSuperieure(): ?Niveau
    {
        return $this->borneSuperieure;
    }

    /**
     * @param Niveau $borneSuperieure
     * @return MetierNiveau
     */
    public function setBorneSuperieure(Niveau $borneSuperieure): MetierNiveau
    {
        $this->borneSuperieure = $borneSuperieure;
        return $this;
    }

    /**
     * @return Niveau|null
     */
    public function getValeurRecommandee(): ?Niveau
    {
        return $this->valeurRecommandee;
    }

    /**
     * @param Niveau $valeurRecommandee
     * @return MetierNiveau
     */
    public function setValeurRecommandee(Niveau $valeurRecommandee): MetierNiveau
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