<?php

namespace FicheMetier\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Element\Entity\Db\CompetenceType;
use Element\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Element\Entity\Db\Traits\HasApplicationCollectionTrait;
use Element\Entity\Db\Traits\HasCompetenceCollectionTrait;
use Metier\Entity\HasMetierInterface;
use Metier\Entity\HasMetierTrait;
use UnicaenEtat\Entity\Db\HasEtatsInterface;
use UnicaenEtat\Entity\Db\HasEtatsTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FicheMetier implements HistoriqueAwareInterface, HasEtatsInterface, HasMetierInterface,
    HasApplicationCollectionInterface, HasCompetenceCollectionInterface
{
    use HistoriqueAwareTrait;
    use HasMetierTrait;
    use HasEtatsTrait;
    use HasApplicationCollectionTrait;
    use HasCompetenceCollectionTrait;

    private ?int $id = -1;
    private ?bool $hasExpertise = false;
    private ?string $raison = null;
    private ?string $codeFonction = null;

    public Collection $competencesSpecifiques;

    private Collection $activites;
    private Collection $missions;
    private Collection $thematiques;

    public function __construct()
    {
        $this->activites = new ArrayCollection();
        $this->etats = new ArrayCollection();
        $this->missions = new ArrayCollection();
        $this->applications = new ArrayCollection();
        $this->competences = new ArrayCollection();
        $this->competencesSpecifiques = new ArrayCollection();

        $this->thematiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function hasExpertise(): bool
    {
        return ($this->hasExpertise === true);
    }

    public function setExpertise(?bool $has): void
    {
        $this->hasExpertise = $has;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(?string $raison): void
    {
        $this->raison = $raison;
    }

    public function getCodeFonction(): ?string
    {
        return $this->codeFonction;
    }

    public function setCodeFonction(?string $codeFonction): void
    {
        $this->codeFonction = $codeFonction;
    }

    /** @return FicheMetierMission[] */
    public function getMissions(): array
    {
        $missions = $this->missions->toArray();
        usort($missions, function (FicheMetierMission $a, FicheMetierMission $b) {
            return $a->getOrdre() <=> $b->getOrdre();
        });
        return $missions;
    }

    /** Compétences ***************************************************************************************************/

    /** Les compétences "standards" sont gérées dans le trait HasCompetenceCollection */

    public function getCompetencesSpecifiquesCollection(): Collection
    {
        return $this->competencesSpecifiques;
    }


    /** @return CompetenceElement[] */
    public function getCompetencesSpecifiques(bool $histo = false): array
    {
        $competences = $this->competencesSpecifiques->toArray();
        if (!$histo) $competences = array_filter($competences, function (CompetenceElement $element) {
            return $element->estNonHistorise();
        });
        return $competences;
    }

    /** @return CompetenceElement[] */
    public function getCompetenceSpecifiqueListe(bool $avecHisto = false) : array
    {
        $competences = [];
        /** @var CompetenceElement $competenceElement */
        foreach ($this->competencesSpecifiques as $competenceElement) {
            if ($avecHisto OR $competenceElement->estNonHistorise()) $competences[$competenceElement->getCompetence()->getId()] = $competenceElement;
        }
        return $competences;
    }

    public function hasCompetenceSpecifique(CompetenceElement $element): bool
    {
        return $this->competencesSpecifiques->contains($element);
    }

    public function addCompetenceSpecifique(CompetenceElement $element): void
    {
        if ($this->hasCompetenceSpecifique($element)) $this->competencesSpecifiques->add($element);
    }

    public function removeCompetenceSpecifique(CompetenceElement $element): void
    {
        if ($this->hasCompetenceSpecifique($element)) $this->competencesSpecifiques->removeElement($element);
    }

    public function clearCompetencesSpecifiques(): void
    {
        $this->competencesSpecifiques->clear();
    }

    /** FONCTION POUR MACRO *******************************************************************************************/

    /**
     * @return string
     */
    public function getActivitesFromFicheMetierAsText(): string
    {
        $texte = '<ul>';
        $missions = $this->getMissions();
        usort($missions, function (FicheMetierMission $a, FicheMetierMission $b) {
            return $a->getOrdre() <=> $b->getOrdre();
        });
        foreach ($missions as $activite) {
            $texte .= '<li>' . $activite->getMission()->getLibelle() . '</li>';
        }
        $texte .= '</ul>';
        return $texte;
    }

    /** @noinspection PhpUnused */
    public function getIntitule(): string
    {
        $metier = $this->getMetier();
        if ($metier === null) return "Aucun métier est associé.";
        return $metier->getLibelle();
    }

    /**
     * Utiliser dans la macro FICHE_METIER#MISSIONS_PRINCIPALES
     * @noinspection PhpUnused
     */
    public function getMissionsAsList(): string
    {
        $texte = "";
        foreach ($this->getMissions() as $mission) {
            $texte .= "<h3 class='mission-principale'>" . $mission->getMission()->getLibelle() . "</h3>";
            $activites = $mission->getMission()->getActivites();
            $texte .= "<ul>";
            foreach ($activites as $activite) {
                $texte .= "<li>";
                $texte .= $activite->getLibelle();
                $texte .= "</li>";
            }
            $texte .= "</ul>";
        }
        return $texte;
    }

    /** @noinspection PhpUnused */
    public function getCompetences(): string
    {
        $competences = $this->getCompetenceListe();

        $texte = "<ul>";
        foreach ($competences as $competence) {
            $texte .= "<li>";
            $texte .= $competence->getCompetence()->getLibelle();
            $texte .= "</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    /** @noinspection PhpUnused */
    public function getComptencesByType(int $typeId): string
    {
        $competences = $this->getCompetenceListe();
        $competences = array_map(function (CompetenceElement $c) {
            return $c->getCompetence();
        }, $competences);
        $competences = array_filter($competences, function (Competence $c) use ($typeId) {
            return $c->getType()->getId() === $typeId;
        });
        usort($competences, function (Competence $a, Competence $b) {
            return $a->getLibelle() <=> $b->getLibelle();
        });

        if (empty($competences)) return "";

        $competence = $competences[0];
        $competenceType = "";
        switch ($competence->getCompetence()->getType()->getId()) {
            case 1 :
                $competenceType = "Compétences comportementales";
                break;
            case 2 :
                $competenceType = "Compétences opérationnelles";
                break;
            case 3 :
                $competenceType = "Connaissances";
                break;
        }

        $texte = "<h3>" . $competenceType . "</h3>";
        $texte .= "<ul>";
        foreach ($competences as $competence) {
            $texte .= "<li>";
            $texte .= $competence->getLibelle();
            $texte .= "</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    /** @noinspection PhpUnused */
    public function getConnaissances(): string
    {
        return $this->getComptencesByType(CompetenceType::CODE_CONNAISSANCE);
    }

    /** @noinspection PhpUnused */
    public function getCompetencesOperationnelles(): string
    {
        return $this->getComptencesByType(CompetenceType::CODE_OPERATIONNELLE);
    }

    /** @noinspection PhpUnused */
    public function getCompetencesComportementales(): string
    {
        return $this->getComptencesByType(CompetenceType::CODE_COMPORTEMENTALE);
    }

    /** @noinspection PhpUnused */
    public function getApplicationsAffichage(): string
    {
        $applications = $this->getApplicationListe();

        $texte = "<ul>";
        /** @var ApplicationElement $applicationElement */
        foreach ($applications as $applicationElement) {
            $application = $applicationElement->getApplication();
            $texte .= "<li>" . $application->getLibelle() . "</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    /** @noinspection PhpUnused */
    public function toStringThematiques(): string
    {
        /** @var ThematiqueElement[] $thematiques */
        $thematiques = $this->thematiques->toArray();
        $thematiques = array_filter($thematiques, function (ThematiqueElement $a) {
            return $a->estNonHistorise() && $a->getType()->estNonHistorise();
        });
        usort($thematiques, function (ThematiqueElement $a, ThematiqueElement $b) {
            return $a->getType()->getOrdre() <=> $b->getType()->getOrdre();
        });

        $texte = "<table>";
        $texte .= "<thead><tr><th>Libelle</th><th>Niveau</th></tr></thead>";
        $texte .= "<tbody>";
        foreach ($thematiques as $thematique) {
            $texte .= "<tr>";
            $texte .= "<td>" . $thematique->getType()->getLibelle() . "</td>";
            $texte .= "<td>" . $thematique->getNiveauMaitrise()->getLibelle() . "</td>";
            $texte .= "</tr>";
        }
        $texte .= "</tbody>";
        $texte .= "</table>";
        return $texte;
    }

}