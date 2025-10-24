<?php

namespace FicheMetier\Entity\Db;

use Application\Provider\Etat\FicheMetierEtats;
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

    private Collection $activites;
    private Collection $missions;
    private Collection $tendances;
    private Collection $thematiques;

    private ?string $raw = null;

    public function __construct()
    {
        $this->activites = new ArrayCollection();
        $this->etats = new ArrayCollection();
        $this->missions = new ArrayCollection();
        $this->applications = new ArrayCollection();
        $this->competences = new ArrayCollection();

        $this->tendances = new ArrayCollection();
        $this->thematiques = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaw(): ?string
    {
        return $this->raw;
    }

    public function setRaw(?string $raw): void
    {
        $this->raw = $raw;
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

    public function addMission(FicheMetierMission $ficheMetierMission): void
    {
        $this->missions->add($ficheMetierMission);
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

    /** @noinspection PhpUnused */
    public function toStringResume(array $parameters = []): string
    {
        $on = $parameters[0] ?? true;
        if ($on === false) return "";

        $html = <<<EOS
<h2>Résumé</h2>

<table style='width:100%; border-collapse: collapse;'>
EOS;

        //metier
        $html .= "<tr><th>Métier</th><td>".$this->getMetier()->getLibelle()."</td>";
        $html .= "<tr><th>Correspondance</th><td><ul>";
        foreach ($this->getMetier()->getCorrespondances() as $correspondance) {
            $html .="<li>";
            $html .= $correspondance->getType()->getLibelleCourt(). " " . $correspondance->getCategorie();
            $html .="</li>";
        }
        $html .="</ul></td>";
        $html .= "<tr><th>Famille</th><td><ul>";
        foreach ($this->getMetier()->getFamillesProfessionnelles() as $familleProfessionnelle) {
            $html .="<li>";
            $html .= $familleProfessionnelle->getLibelle();
            $html .="</li>";
        }
        $html .="</ul></td>";
        //$html .= "<tr><th>Code Fonction</th><td>".$this->getMetier()->getLibelle()."</td>";
        $html .= "<tr><th>Référence·s</th><td>";
        foreach ($this->getMetier()->getReferences() as $reference) {
            $html .="<li>";
            $html .= $reference->getReferentiel()->getLibelleCourt(). " " . $reference->getCode();
            $html .="</li>";
        }
        $html .= "</td>";
        $html .= "<tr><th>Expertise</th><td>".($this->hasExpertise()?"Oui":"Non")."</td></tr>";
        $html .= "<tr><th>Date de dépôt</th><td>";
        $etat = $this->getEtatActif();
        if ($etat === null) $html .= "État inconnu";
        else {
            $type = $etat->getType();
            if ($type === null) $html .= "Type de l'état inconnu";
            else {
                switch ($type->getCode()) {
                    case FicheMetierEtats::ETAT_VALIDE :
                        $html .= "Validée le ".$etat->getHistoCreation()->format("d/M/Y");
                        break;
                    case FicheMetierEtats::ETAT_REDACTION :
                        $html .= "Créée le ".$etat->getHistoCreation()->format("d/M/Y");
                        break;
                    default :
                        $html .= "Le ".$etat->getHistoCreation()->format("d/M/Y");
                        break;
                }
            }
        }
        $html .= "</td></tr>";


        $html .= <<<EOS
</table>
EOS;

        return $html;
    }
    /**
     * Utiliser dans la macro FICHE_METIER#MISSIONS_PRINCIPALES
     * @noinspection PhpUnused
     */
    public function getMissionsAsList(array $parameters = []): string
    {
        $on = $parameters[0]??true;
        if ($on === false OR empty($this->getMissions()))  return "";

        $texte = "<h2> Mission·s principale·s</h2>";
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
        /** @var Competence[] $competences */
        $competences = array_filter($competences, function (Competence $c) use ($typeId) {
            return $c->getType()->getId() === $typeId;
        });
        usort($competences, function (Competence $a, Competence $b) {
            return $a->getLibelle() <=> $b->getLibelle();
        });

        if (empty($competences)) return "";

        $competence = $competences[0];
        $competenceType = "";
        switch ($competence->getType()->getId()) {
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
    public function getCompetencesSpecifique(): string
    {
        return $this->getComptencesByType(CompetenceType::CODE_SPECIFIQUE);
    }


    /** @noinspection PhpUnused */
    public function toStringRaison(array $parameters = []): string
    {
        $on = $parameters[0]??true;
        if ($on === false OR $this->raison === null)  return "";

        $texte  = "<h2>Raison d'être métier dans l'établissement</h2>";
        $texte .= $this->raison;

        return $texte;
    }

    /** @noinspection PhpUnused */
    public function toStringThematiques(array $parameters = []): string
    {
        $on = $parameters[0]??true;
        if ($on === false) return "";

            /** @var ThematiqueElement[] $thematiques */
        $thematiques = $this->thematiques->toArray();
        $thematiques = array_filter($thematiques, function (ThematiqueElement $a) {
            return $a->estNonHistorise() && $a->getType()->estNonHistorise();
        });
        usort($thematiques, function (ThematiqueElement $a, ThematiqueElement $b) {
            return $a->getType()->getOrdre() <=> $b->getType()->getOrdre();
        });
        if (empty($thematiques)) return "";

        $html = <<<EOS
<h2>Environnement et contexte de travail</h2>

<table style='width:100%; border-collapse: collapse;'>
<thead>
    <tr>
        <th> Libellé </th>
        <th style="width: 11rem;"> Niveau</th>
    </tr>
</thead>
<tbody>
EOS;

        foreach ($thematiques as $thematique) {
            $html .= "<tr>";
            $html .= "<td>" . $thematique->getType()->getLibelle() . "</td>";
            $html .= "<td>" . $thematique->getNiveauMaitrise()->getLibelle() . "</td>";
            $html .= "</tr>";
        }
        $html .= <<<EOS
</tbody>
</table>
EOS;

        return $html;
    }

    /** @noinspection PhpUnused */
    public function toStringTendances(array $parameters = []): string
    {
        $on = $parameters[0]??true;
        if ($on === false) return "";

        /** @var TendanceElement[] $tendances */
        $tendances = $this->tendances->toArray();
        $tendances = array_filter($tendances, function (TendanceElement $a) {
            return $a->estNonHistorise() && $a->getType()->estNonHistorise();
        });
        usort($tendances, function (TendanceElement $a, TendanceElement $b) {
            return $a->getType()->getOrdre() <=> $b->getType()->getOrdre();
        });
        if (empty($tendances)) return "";

        $html = <<<EOS
<h2>Tendances d'évolution </h2>
EOS;

        $html .= "<div class='tendances'>";
        foreach ($tendances as $tendance) {
            $html .= "<div class='tendance'>";
            $html .= "<div class='tendance-libelle'>" . $tendance->getType()->getLibelle() . "</div>";
            $html .= "<div class='tendance-texte'>" . $tendance->getTexte() . "</div>";
            $html .= "</div>";
        }
        $html .= "</div>";
        return $html;
    }
}