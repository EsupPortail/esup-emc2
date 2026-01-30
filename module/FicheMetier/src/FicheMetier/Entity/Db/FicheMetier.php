<?php

namespace FicheMetier\Entity\Db;

use Application\Provider\Etat\FicheMetierEtats;
use Carriere\Entity\Db\Interface\HasFamilleProfessionnelleInterface;
use Carriere\Entity\Db\Interface\HasNiveauCarriereInterface;
use Carriere\Entity\Db\Trait\HasFamilleProfessionnelleTrait;
use Carriere\Entity\Db\Trait\HasNiveauCarriereTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Element\Entity\Db\CompetenceType;
use Element\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Element\Entity\Db\Interfaces\HasCompetenceCollectionInterface;
use Element\Entity\Db\Traits\HasApplicationCollectionTrait;
use Element\Entity\Db\Traits\HasCompetenceCollectionTrait;
use FicheMetier\Entity\Db\Interface\HasActivitesInterface;
use FicheMetier\Entity\Db\Interface\HasMissionsPrincipalesInterface;
use FicheMetier\Entity\Db\Trait\HasActivitesTrait;
use FicheMetier\Entity\Db\Trait\HasMissionsPrincipalesTrait;
use Referentiel\Entity\Db\Interfaces\HasReferenceInterface;
use Referentiel\Entity\Db\Referentiel;
use Referentiel\Entity\Db\Traits\HasReferenceTrait;
use UnicaenEtat\Entity\Db\HasEtatsInterface;
use UnicaenEtat\Entity\Db\HasEtatsTrait;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class FicheMetier implements
    HistoriqueAwareInterface, HasEtatsInterface, HasNiveauCarriereInterface,
    HasActivitesInterface, HasMissionsPrincipalesInterface, HasFamilleProfessionnelleInterface,
    HasApplicationCollectionInterface, HasCompetenceCollectionInterface,
    HasReferenceInterface
{
    use HistoriqueAwareTrait;
    use HasEtatsTrait;
    use HasNiveauCarriereTrait;
    use HasActivitesTrait, HasMissionsPrincipalesTrait, HasFamilleProfessionnelleTrait;
    use HasApplicationCollectionTrait, HasCompetenceCollectionTrait;

    use HasReferenceTrait;

    private ?int $id = null;
    private ?string $libelle = null;
    private ?string $raison = null;
    private ?CodeFonction $codeFonction = null;
    private ?string $codesEmploiType = null;

    public ?string $lienWeb = null;
    public ?string $lienPdf = null;

    private Collection $tendances;
    private Collection $thematiques;

    private ?string $raw = null;

    /** @var TendanceType[] */
    private array $tendancesTypes = [];

    public function setTendancesTypes(array $types): void
    {
        $this->tendancesTypes = $types;
    }

    /** @var ThematiqueType[] */
    private array $thematiquesTypes = [];

    public function setThematiquesTypes (array $types): void
    {
        $this->thematiquesTypes  = $types;
    }


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

    public function getLibelle(bool $fillEmptyLibelle = true): ?string
    {
        if (!$fillEmptyLibelle) { return $this->libelle; }
        return $this->libelle??"<span class='missing-data'>Aucune libelle pour la fiche Id:".$this->id."</span>";
    }

    public function setLibelle(?string $libelle): void
    {
        $this->libelle = $libelle;
    }

    public function getCodesEmploiType(): ?string
    {
        return $this->codesEmploiType;
    }

    public function setCodesEmploiType(?string $codesEmploiType): void
    {
        $this->codesEmploiType = $codesEmploiType;
    }



    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function setRaison(?string $raison): void
    {
        $this->raison = $raison;
    }

    public function getCodeFonction(): ?CodeFonction
    {
        return $this->codeFonction;
    }

    public function setCodeFonction(?CodeFonction $codeFonction): void
    {
        $this->codeFonction = $codeFonction;
    }

    public function hasLink(): bool
    {
        return $this->lienWeb !== null || $this->lienPdf !== null;
    }

    public function getLienWeb(): ?string
    {
        return $this->lienWeb;
    }

    public function setLienWeb(?string $lienWeb): void
    {
        $this->lienWeb = $lienWeb;
    }

    public function getLienPdf(): ?string
    {
        return $this->lienPdf;
    }

    public function setLienPdf(?string $lienPdf): void
    {
        $this->lienPdf = $lienPdf;
    }

    /** @return TendanceElement[] */
    public function getTendances(): array
    {
        $tendances = [];
        /** @var TendanceElement $tendance */
        foreach ($this->tendances as $tendance) {
            if ($tendance->estNonHistorise()) {
                $tendances[$tendance->getType()->getCode()] = $tendance;
            }
        }
        return $tendances;
    }

    public function addTendance(TendanceElement $tendance): void
    {
        $this->tendances->add($tendance);
    }

    public function clearTendances(): void
    {
        $this->tendances->clear();
    }

    /** @return ThematiqueElement[] */
    public function getThematiques(): array
    {
        $thematiques = [];
        /** @var ThematiqueElement $thematique */
        foreach ($this->thematiques as $thematique) {
            if ($thematique->estNonHistorise()) {
                $thematiques[$thematique->getType()->getCode()] = $thematique;
            }
        }
        return $thematiques;
    }

    public function addThematique(ThematiqueElement $thematique): void
    {
        $this->thematiques->add($thematique);
    }

    public function clearThematique(): void
    {
        $this->thematiques->clear();
    }

    /** FONCTION POUR MACRO *******************************************************************************************/

    /**
     * @return string
     */
    public function getActivitesFromFicheMetierAsText(): string
    {
        $texte = '<ul>';
        $activites = $this->getActivites();
        usort($activites, function (ActiviteElement $a, ActiviteElement $b) {
            return $a->getPosition() <=> $b->getPosition();
        });
        foreach ($activites as $activite) {
            $texte .= '<li>' . $activite->getActivite()->getLibelle() . '</li>';
        }
        $texte .= '</ul>';
        return $texte;
    }

    /** @noinspection PhpUnused */
    public function getIntitule(): ?string
    {
        return $this->getLibelle();
    }

    /** @noinspection PhpUnused */
    public function toStringResume(): string
    {
        $html = <<<EOS
<h2>Résumé</h2>

<table style='width:100%; border-collapse: collapse;'>
EOS;

        //metier
        $html .= "<tr><th>Spécialité</th>";
        $html .= "<td>" . $this->getFamilleProfessionnelle()?->getCorrespondance()?->getLibelleLong() . "</td>";

        $html .= "<tr><th>Famille professionnelle</th>";
        $html .= "<td>" . $this->getFamilleProfessionnelle()?->getLibelle() . "</td>";
        //$html .= "<tr><th>Code Fonction</th><td>".$this->getMetier()->getLibelle()."</td>";
        $html .= "<tr><th>Référence·s</th>";
        $html .= "<td>". $this->printReference()."</td>";
        $html .= "<tr><th>Date de dépôt</th><td>";
        $etat = $this->getEtatActif();
        if ($etat === null) $html .= "État inconnu";
        else {
            $type = $etat->getType();
            if ($type === null) $html .= "Type de l'état inconnu";
            else {
                switch ($type->getCode()) {
                    case FicheMetierEtats::ETAT_VALIDE :
                        $html .= "Validée le " . $etat->getHistoCreation()->format("d/M/Y");
                        break;
                    case FicheMetierEtats::ETAT_REDACTION :
                        $html .= "Créée le " . $etat->getHistoCreation()->format("d/M/Y");
                        break;
                    default :
                        $html .= "Le " . $etat->getHistoCreation()->format("d/M/Y");
                        break;
                }
            }
        }
        $html .= "</td></tr>";
        $html .= "<tr><th>Code Fonction</th><td>" . ($this->getCodeFonction() ? $this->getCodeFonction()->computeCode() : "N.C.") . "</td></tr>";


        $html .= <<<EOS
</table>
EOS;

        return $html;
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
    public function getComptencesByType(string $code): string
    {
        $competences = $this->getCompetenceListe();
        $competences = array_map(function (CompetenceElement $c) {
            return $c->getCompetence();
        }, $competences);
        /** @var Competence[] $competences */
        $competences = array_filter($competences, function (Competence $c) use ($code) {
            return $c->getType()->getId() === $code;
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
    public function toStringRaison(): string
    {
        if ($this->raison === null) {
            return "Aucune raison communiquée";
        }
        return $this->raison;
    }

    /**
     * @noinspection PhpUnused
     * N.B. On suppose que l'attribut $thematiquesTypes est initialisé avec la liste des types de thematiques
     */
    public function toStringThematiques(string $all = "false"): string
    {
        $listing = [];
        foreach ($this->getThematiques() as $thematique) {
            $listing[$thematique->getType()->getCode()] = $thematique;
        }
        $html = <<<EOS
<table style='width:100%; border-collapse: collapse;' id="environnement">
<thead>
    <tr>
        <th id="libelle"> Libellé </th>
        <th id="niveau"> Niveau</th>
    </tr>
</thead>
<tbody>
EOS;

        foreach ($this->thematiquesTypes as $type) {
            if ($all === "1" OR $type->isObligatoire() OR isset($listing[$type->getCode()])) {
                $thematique = $listing[$type->getCode()]??null;
                $html .= "<tr>";
                $html .= "<td>" . $type->getLibelle() . "</td>";
                $html .= "<td>" . ($thematique?->getNiveauMaitrise()?$thematique->getNiveauMaitrise()->getLibelle():"non précisé") . "</td>";
                $html .= "</tr>";
            }
        }
        $html .= "</tbody></table>";
        return $html;
    }

    /** @noinspection PhpUnused
     * N.B. On suppose que l'attribut $tendancesTypes est initialisé avec la liste des types de tendance
     **/

    public function toStringTendances(string $all = "false"): string
    {
        $listing = [];
        foreach ($this->getTendances() as $tendance) $listing[$tendance->getType()->getCode()] = $tendance;

        $html = "<div class='tendances'>";
        foreach ($this->tendancesTypes as $type) {
            if ($all === "1" OR $type->isObligatoire() OR isset($listing[$type->getCode()])) {
                $tendance = $listing[$type->getCode()]??null;
                $html .= "<div class='tendance-libelle'>" . $type->getLibelle() . "</div>";
                $html .= "<div class='tendance-texte'>" . ($tendance?$tendance->getTexte():"non précisé") . "</div>";
            }
        }
        $html .= "</div>";
        return $html;
    }

    /** @noinspection PhpUnused */
    public function toStringNiveauFonction(): string
    {
        if ($this->getCodeFonction() AND $this->getCodeFonction()->getNiveauFonction()) return $this->getCodeFonction()->getNiveauFonction()->getLibelle();
        return "Non précisé";
    }

    /** @noinspection PhpUnused */
    public function toStringCodeFonction(): string
    {
        if ($this->getCodeFonction()) return $this->getCodeFonction()->computeCode();
        return "Non précisé";
    }

    /** @noinspection PhpUnused */
    public function toStringEtatActif(): string
    {
        $etat = $this->getEtatActif();
        if ($etat === null) return "Aucun état actif";
        $type = $etat->getType();
        if ($type === null) return "Aucun type d'état connu";
        return $type->getLibelle();
    }

    /** @noinspection PhpUnused */
    public function toStringEtatActifDate(): string
    {
        $etat = $this->getEtatActif();
        if ($etat === null) return "Aucun état actif";
        return $etat->getDate()??"Aucune date connue";
    }

    /** @noinspection PhpUnused */
    public function toStringSpecialite(): string
    {
        $famille = $this->getFamilleProfessionnelle();
        if ($famille === null) return "Aucune spécialité connue";
        $specialite = $famille->getCorrespondance();
        if ($specialite === null) return "Aucune spécialité connue";
        return $specialite->getLibelleLong();
    }

    /** @noinspection PhpUnused */
    public function toStringFamille(): string
    {
        $famille = $this->getFamilleProfessionnelle();
        if ($famille === null) return "Aucune famille professionnelle connue";
        return $famille->getLibelle();
    }

    public function getMissionByReference(?Referentiel $referentiel, ?string $reference): ?MissionElement
    {
        foreach ($this->missions as $mission) {
            if ($mission->getMission()->getReferentiel() === $referentiel && $mission->getMission()->getReference() === $reference) return $mission;
        }
        return null;
    }

}