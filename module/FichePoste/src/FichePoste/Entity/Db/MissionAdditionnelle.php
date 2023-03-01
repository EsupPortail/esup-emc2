<?php


use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class MissionAdditionnelle implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var int */
    private $id;
    /** @var \Application\Entity\Db\SpecificitePoste */
    private $specificite;
    /** @var \Application\Entity\Db\Activite */
    private $activite;
    /** @var string|null */
    private $retrait;
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
     * @return \Application\Entity\Db\SpecificitePoste|null
     */
    public function getSpecificite(): ?\Application\Entity\Db\SpecificitePoste
    {
        return $this->specificite;
    }

    /**
     * @param \Application\Entity\Db\SpecificitePoste $specificite
     * @return \Application\Entity\Db\SpecificiteActivite
     */
    public function setSpecificite(\Application\Entity\Db\SpecificitePoste $specificite): MissionAdditionnelle
    {
        $this->specificite = $specificite;
        return $this;
    }

    /**
     * @return \Application\Entity\Db\Activite|null
     */
    public function getActivite(): ?\Application\Entity\Db\Activite
    {
        return $this->activite;
    }

    /**
     * @param \Application\Entity\Db\Activite $activite
     * @return \Application\Entity\Db\SpecificiteActivite
     */
    public function setActivite(\Application\Entity\Db\Activite $activite): MissionAdditionnelle
    {
        $this->activite = $activite;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getRetrait(): ?string
    {
        return $this->retrait;
    }

    /**
     * @param string|null $retrait
     * @return \Application\Entity\Db\SpecificiteActivite
     */
    public function setRetrait(?string $retrait): MissionAdditionnelle
    {
        $this->retrait = $retrait;
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
     * @return \Application\Entity\Db\SpecificiteActivite
     */
    public function setDescription(?string $description): MissionAdditionnelle
    {
        $this->description = $description;
        return $this;
    }
}