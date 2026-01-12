<?php

namespace Element\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Referentiel\Entity\Db\Interfaces\HasReferenceInterface;
use Referentiel\Entity\Db\Traits\HasReferenceTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Competence implements HistoriqueAwareInterface, HasReferenceInterface {
    use HistoriqueAwareTrait;
    use HasReferenceTrait;

    const COMPETENCE_HEADER_ID = "Id_compétence";
    const COMPETENCE_HEADER_LIBELLE = "Compétence";
    const COMPETENCE_HEADER_TYPE = "Type";
    const COMPETENCE_HEADER_THEME = "Thème";
    const COMPETENCE_HEADER_DEFINITION = "Définition";
    const COMPETENCE_HEADER_DISCIPLINE = "Discipline";
    const COMPETENCE_HEADER_SYNONYMES = "Synonymes";
    const COMPETENCE_HEADER_CODES_EMPLOI_TYPE = "Codes Emploi Type";
    const COMPETENCE_HEADER_CODES_FONCTION = "Codes Fonction";


    private ?int $id = null;
    private ?string $libelle = null;
    private ?string $description = null;
    private ?CompetenceDiscipline $discipline = null;
    private ?CompetenceType $type = null;
    private ?CompetenceTheme $theme = null;
    private ?string $codesEmploiType = null;
    private ?string $codesFonction = null;
    private ?string $raw = null;

    private Collection $synonymes;


    public function __construct()
    {
        $this->synonymes = new ArrayCollection();
    }
    public function getId() : ?int
    {
        return $this->id;
    }

    public function getLibelle() : ?string
    {
        return $this->libelle;
    }

    public function setLibelle(?string $libelle) : void
    {
        $this->libelle = $libelle;
    }

    public function getDescription() : ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description) : void
    {
        $this->description = $description;
    }

    public function getDiscipline(): ?CompetenceDiscipline
    {
        return $this->discipline;
    }

    public function setDiscipline(?CompetenceDiscipline $discipline): void
    {
        $this->discipline = $discipline;
    }

    public function getType() : ?CompetenceType
    {
        return $this->type;
    }

    public function setType(?CompetenceType $type) : void
    {
        $this->type = $type;
    }

    public function getTheme() : ?CompetenceTheme
    {
        return $this->theme;
    }

    public function setTheme(?CompetenceTheme $theme) : void
    {
        $this->theme = $theme;
    }

    public function getCodesFonction(): ?string
    {
        return $this->codesFonction;
    }

    public function setCodesFonction(?string $codesFonction): void
    {
        $this->codesFonction = $codesFonction;
    }

    public function getCodesEmploiType(): ?string
    {
        return $this->codesEmploiType;
    }

    public function setCodesEmploiType(?string $codesEmploiType): void
    {
        $this->codesEmploiType = $codesEmploiType;
    }


    /** Gestion des synonymes *****************************************************************************************/

    /** @return CompetenceSynonyme[] */
    public function getSynonymes(): array
    {
        return $this->synonymes->toArray();
    }

    public function isSynonyme(string $libelle) : bool
    {
        foreach ($this->getSynonymes() as $synonyme) {
            if ($synonyme->getLibelle() === $libelle) return true;
        }
        return false;
    }

    public function addSynonyme(CompetenceSynonyme $synonyme): void
    {
        $this->synonymes->add($synonyme);
    }

    public function removeSynonyme(CompetenceSynonyme $synonyme): void
    {
        $this->synonymes->removeElement($synonyme);
    }

    public function clearSynonymes(): void
    {
        $this->synonymes->clear();
    }

    /** Gestion de texte brut associé à une compétence importée *******************************************************/

    public function getRaw(): ?string
    {
        return $this->raw;
    }

    public function setRaw(?string $raw): void
    {
        $this->raw = $raw;
    }

}