<?php

namespace EmploiRepere\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class EmploiRepere implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $code = null;
    private ?string $libelle = null;
    private ?string $description = null;

    private Collection $codesFonctionsFichesMetiers;

    public function __construct() {
        $this->codesFonctionsFichesMetiers = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    //lien_cf_fm
    /** @return EmploiRepereCodeFonctionFicheMetier[] */
    public function getCodesFonctionsFichesMetiers(): array
    {
        return $this->codesFonctionsFichesMetiers->toArray();
    }

}
