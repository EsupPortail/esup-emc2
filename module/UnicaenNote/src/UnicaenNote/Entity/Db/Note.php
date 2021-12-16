<?php

namespace UnicaenNote\Entity\Db;

use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class Note implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    const DEFAULT_ORDRE = 9999;

    /** @var integer */
    private $id;
    /** @var Type */
    private $type;
    /** @var PorteNote */
    private $portenote;
    /** @var integer */
    private $ordre;
    /** @var string */
    private $texte;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Type|null
     */
    public function getType(): ?Type
    {
        return $this->type;
    }

    /**
     * @param Type|null $type
     * @return Note
     */
    public function setType(?Type $type): Note
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return PorteNote|null
     */
    public function getPortenote(): ?PorteNote
    {
        return $this->portenote;
    }

    /**
     * @param PorteNote|null $portenote
     * @return Note
     */
    public function setPortenote(?PorteNote $portenote): Note
    {
        $this->portenote = $portenote;
        return $this;
    }

    /**
     * @return int
     */
    public function getOrdre(): int
    {
        if ($this->ordre === null) return Note::DEFAULT_ORDRE;
        return $this->ordre;
    }

    /**
     * @param ?int $ordre
     * @return Note
     */
    public function setOrdre(?int $ordre): Note
    {
        $this->ordre = $ordre;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getTexte(): ?string
    {
        return $this->texte;
    }

    /**
     * @param string|null $texte
     * @return Note
     */
    public function setTexte(?string $texte): Note
    {
        $this->texte = $texte;
        return $this;
    }

}