<?php

namespace UnicaenEtat\Entity\Db;

use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Action implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var ActionType */
    private $type;
    /** @var string */
    private $libelle;

    /** @var Etat */
    private $etat;
    /** @var integer */
    private $position;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Action
     */
    public function setCode(string $code): Action
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return ActionType
     */
    public function getType(): ActionType
    {
        return $this->type;
    }

    /**
     * @param ActionType $type
     * @return Action
     */
    public function setType(ActionType $type): Action
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string
     */
    public function getLibelle(): string
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return Action
     */
    public function setLibelle(string $libelle): Action
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return Etat
     */
    public function getEtat(): Etat
    {
        return $this->etat;
    }

    /**
     * @param Etat $etat
     * @return Action
     */
    public function setEtat(Etat $etat): Action
    {
        $this->etat = $etat;
        return $this;
    }

    /**
     * @return int
     */
    public function getPosition(): int
    {
        return $this->position;
    }

    /**
     * @param int $position
     * @return Action
     */
    public function setPosition(int $position): Action
    {
        $this->position = $position;
        return $this;
    }


}