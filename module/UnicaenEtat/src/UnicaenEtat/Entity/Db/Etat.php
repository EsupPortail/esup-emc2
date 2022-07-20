<?php

namespace UnicaenEtat\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Etat implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $code;
    /** @var EtatType */
    private $type;
    /** @var string */
    private $libelle;
    /** @var string */
    private $icone;
    /** @var string */
    private $couleur;
    /** @var ArrayCollection ($Action) */
    private $actions;
    /** @var integer */
    private $ordre;

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
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     * @return Etat
     */
    public function setCode(string $code): Etat
    {
        $this->code = $code;
        return $this;
    }

    /**
     * @return EtatType|null
     */
    public function getType(): ?EtatType
    {
        return $this->type;
    }

    /**
     * @param EtatType $type
     * @return Etat
     */
    public function setType(EtatType $type): Etat
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     * @return Etat
     */
    public function setLibelle(string $libelle): Etat
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getIcone(): ?string
    {
        return $this->icone;
    }

    /**
     * @param string|null $icone
     * @return Etat
     */
    public function setIcone(?string $icone): Etat
    {
        $this->icone = $icone;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    /**
     * @param string|null $couleur
     * @return Etat
     */
    public function setCouleur(?string $couleur): Etat
    {
        $this->couleur = $couleur;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    /**
     * @param int|null $ordre
     * @return Etat
     */
    public function setOrdre(?int $ordre): Etat
    {
        $this->ordre = $ordre;
        return $this;
    }

    /** Action  *************************************************************************************/

    /**
     * @return array|null
     */
    public function getActions()
    {
        if ($this->actions === null) return null;
        return $this->actions->toArray();
    }
}