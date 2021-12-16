<?php

namespace UnicaenNote\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenApp\Entity\HistoriqueAwareInterface;
use UnicaenApp\Entity\HistoriqueAwareTrait;

class PorteNote implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $accroche;
    /** @var ArrayCollection (Note) */
    private $notes;

    public function __construct() {
        $this->notes = new ArrayCollection();
    }

    /**
     * @return integer
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getAccroche(): ?string
    {
        return $this->accroche;
    }

    /**
     * @param string|null $accroche
     * @return PorteNote
     */
    public function setAccroche(?string $accroche): PorteNote
    {
        $this->accroche = $accroche;
        return $this;
    }

    /** GESTION DES NOTES *********************************************************************************************/

    /**
     * @return Note[]
     */
    public function getNotes() : array
    {
        $notes = $this->notes->toArray();
        usort($notes, function (Note $a, Note $b) {
            if ($a->getOrdre() !== $b->getOrdre())
                return $a->getOrdre() > $b->getOrdre();
            return $a->getHistoCreation() > $b->getHistoCreation();
        });
        return $notes;
    }

    /**
     * @param Note $note
     * @return boolean
     */
    public function hasNote(Note $note) : bool
    {
        return $this->notes->contains($note);
    }

    /**
     * @param Note $note
     * @return PorteNote
     */
    public function addNote(Note $note) : PorteNote
    {
        if (!$this->hasNote($note)) $this->notes->add($note);
        return $this;
    }

    /**
     * @param Note $note
     * @return PorteNote
     */
    public function removeNote(Note $note) : PorteNote
    {
        $this->notes->removeElement($note);
        return $this;
    }

}