<?php

namespace FicheMetier\Entity\Db;

use Carriere\Entity\Db\NiveauFonction;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Metier\Entity\Db\FamilleProfessionnelle;
use RuntimeException;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

//todo à décaller dans le module EmploiRepere
class CodeFonction implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?NiveauFonction $niveauFonction = null;
    private ?FamilleProfessionnelle $familleProfessionnelle = null;
    private ?string $description = null;
    private ?string $code = null;

    private Collection $fichesmetiers;

    public function __construct()
    {
        $this->fichesmetiers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getNiveauFonction(): ?NiveauFonction
    {
        return $this->niveauFonction;
    }

    public function setNiveauFonction(?NiveauFonction $niveauFonction): void
    {
        $this->niveauFonction = $niveauFonction;
    }

    public function getFamilleProfessionnelle(): ?FamilleProfessionnelle
    {
        return $this->familleProfessionnelle;
    }

    public function setFamilleProfessionnelle(?FamilleProfessionnelle $familleProfessionnelle): void
    {
        $this->familleProfessionnelle = $familleProfessionnelle;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): void
    {
        $this->code = $code;
    }

    /** @return FicheMetier[] */
    public function getFichesMetiers(): array
    {
        return $this->fichesmetiers->toArray();
    }

    /** FACADE ********************************************************************************************************/

    public function computeCode(): ?string
    {
        if ($this->getNiveauFonction() === null) throw new RuntimeException("Le code fonction ne possède pas de niveau de fonction",-1);
        if ($this->getFamilleProfessionnelle() === null) throw new RuntimeException("Le code fonction ne possède pas de niveau de fonction",-1);
        $code = $this->getNiveauFonction()->getCode()??"????";
        $code .= $this->getFamilleProfessionnelle()->getCorrespondance()?->getCategorie()??"?";
        $code .= $this->getFamilleProfessionnelle()->getPosition()??"?";
        return $code;
    }
}
