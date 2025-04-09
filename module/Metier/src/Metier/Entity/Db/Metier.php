<?php

namespace Metier\Entity\Db;

use Application\Entity\Db\Agent;
use Carriere\Entity\Db\Categorie;
use Carriere\Entity\Db\NiveauEnveloppe;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use FicheMetier\Entity\Db\FicheMetier;
use Metier\Service\Metier\MetierService;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Metier implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $libelle = null;
    private ?string $libelleFeminin = null;
    private ?string $libelleMasculin = null;

    private ?Categorie $categorie = null;
    private ?NiveauEnveloppe $niveaux = null;

    private Collection $domaines;
    private Collection $famillesProfessionnelles;
    private Collection $references;
    private Collection $fichesMetiers;

    public function __construct()
    {
        $this->references = new ArrayCollection();
        $this->fichesMetiers = new ArrayCollection();
        $this->domaines = new ArrayCollection();
        $this->famillesProfessionnelles = new ArrayCollection();
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    /** Gestion du libellé des métiers ********************************************************************************/

    public function getLibelle(bool $inclusif = true) : ?string
    {
        if ($inclusif === true AND $this->libelleFeminin !== null AND $this->libelleMasculin !== null) {
            $inclusif = MetierService::computeEcritureInclusive($this->getLibelleFeminin(), $this->getLibelleMasculin());
            return $inclusif;
        }
        return $this->libelle;
    }

    public function setLibelle(?string $libelle) : void
    {
        $this->libelle = $libelle;
    }

    public function getLibelleFeminin(): ?string
    {
        return ($this->libelleFeminin)?:$this->libelle;
    }

    public function setLibelleFeminin(?string $libelleFeminin): void
    {
        $this->libelleFeminin = $libelleFeminin;
    }

    public function getLibelleMasculin(): ?string
    {
        return ($this->libelleMasculin)?:$this->libelle;
    }

    public function setLibelleMasculin(?string $libelleMasculin): void
    {
        $this->libelleMasculin = $libelleMasculin;
    }

    /** Catégorie et Niveaux ***************************************************************************************/

    public function getCategorie() : ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie) : void
    {
        $this->categorie = $categorie;
    }

    public function getNiveaux(): ?NiveauEnveloppe
    {
        return $this->niveaux;
    }

    public function setNiveaux(?NiveauEnveloppe $niveaux): void
    {
        $this->niveaux = $niveaux;
    }

    /** Gestion des collections associés au métier ********************************************************************/

    /** @return FicheMetier[] */
    public function getFichesMetiers() : array
    {
        return $this->fichesMetiers->toArray();
    }

    /**
     * @return Reference[]
     */
    public function getReferences() : array
    {
        return $this->references->toArray();
    }

    /** @return Domaine[] */
    public function getDomaines() : array
    {
        $domaines =  $this->domaines->toArray();
        usort($domaines, function (Domaine $a, Domaine $b) { return $a->getLibelle() <=> $b->getLibelle();});
        return $domaines;
    }

    public function clearDomaines() : void
    {
        $this->domaines->clear();
    }

    public function addDomaine(Domaine $domaine): void
    {
        $this->domaines->add($domaine);
    }

    /** @return FamilleProfessionnelle[] */
    public function getFamillesProfessionnelles() : array
    {
        $familles =  $this->famillesProfessionnelles->toArray();
        usort($familles, function (FamilleProfessionnelle $a, FamilleProfessionnelle $b) { return $a->getLibelle() <=> $b->getLibelle();});
        return $familles;
    }

    public function clearFamillesProfessionnelles() : void
    {
        $this->famillesProfessionnelles->clear();
    }

    public function addFamillesProfessionnelles(FamilleProfessionnelle $famille): void
    {
        $this->famillesProfessionnelles->add($famille);
    }

    /** Fonctions pour affichage **************************************************************************************/

    public function generateTooltip() : string
    {
        $html  = '';
        /** Ligne sur le metier **/
        $html .= '<span class="highlight metier">';
        $html .= htmlentities($this->getLibelle(), ENT_QUOTES);
        $html .= '</span><br/>';

        /** Lignes sur les domaines du metier **/
        $html .= '<strong>Domaines</strong> :';
        $html .= '<ul>';
        foreach ($this->getDomaines() as $domaine) {
            $html .= '<li>';
            $html .= htmlentities($domaine->getLibelle(), ENT_QUOTES);
            $html .= '</li>';
        }
        $html .= '</ul>';
        /** Ligne sur les refs */
        $html .= '<strong>Références</strong> :';
        foreach ($this->getReferences() as $reference) {
            $html .= '<span class="badge" style="margin:0.25rem;">';
            $html .= htmlentities($reference->getReferentiel()->getLibelleCourt(), ENT_QUOTES);
            $html .= ' - ';
            $html .= htmlentities($reference->getCode(), ENT_QUOTES);
            $html .= '</span><br/>';
        }
        return '<span style="text-align:left;">'.$html.'</span>';
    }

    public function getReferencesAffichage() : string
    {
        $texte = "Réference" . ((count($this->getReferences()) > 1)?"s":"") . " : ";
        $texte .= "<ul id='reference'>";
        foreach ($this->getReferences() as $reference) {
            $texte .= "<li>";
            $texte .= "<a href='". $reference->getUrl()."'>";
            $texte .= "<span class='reference'>";
            $texte .= $reference->getReferentiel()->getLibelleCourt().' - '.$reference->getCode();
            $texte .= "</span>";
            $texte .= "</a>";
            $texte .= "</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    /** @noinspection PhpUnused */
    public function getDomainesAffichage() : string
    {
        $domaines = $this->getDomaines();
        usort($domaines, function (Domaine $a, Domaine $b) { return $a->getLibelle() <=> $b->getLibelle(); });

        $texte = "Domaine" . ((count($domaines) > 1)?"s":"") . " : ";
        $texte .= "<ul id='domaine'>";
        foreach ($domaines as $domaine) $texte .= "<li>".$domaine->getLibelle()."</li>";
        $texte .= "</ul>";
        return $texte;
    }

    /** @noinspection PhpUnused */
    public function toStringDomaines() : string
    {
        $texte  = "<table style='width: 25rem;'>";
        $texte .= "    <thead style='border:2px solid black; background: lightyellow;'><tr>";
        $texte .= "        <th style='padding: 1rem;'>Domaine(s) </th>";
        $texte .= "    </tr></thead>";

        /** @var Domaine $domaine */
        $domaines = $this->domaines->toArray();
        usort($domaines, function (Domaine $a, Domaine $b) { return $a->getLibelle() <=> $b->getLibelle(); });
        $texte .= "<tbody style='border:2px solid black;'>";
        foreach ($domaines as $domaine) {
            $texte .= "<tr><td style='padding:0.25rem 1rem;'>" . $domaine->getLibelle() . "</td></tr>";
        }
        $texte .= "</tbody>";
        $texte .= "</table>";
        return $texte;
    }

    public function getLibelleGenre(?Agent $agent) : string
    {
        if ($agent === null) return $this->getLibelle();
        if ($agent->getSexe() === 'M' AND $this->libelleMasculin !== null) return $this->getLibelleMasculin();
        if ($agent->getSexe() === 'F' AND $this->libelleFeminin !== null) return $this->getLibelleFeminin();
        return $this->getLibelle();
    }

    /**
     * @param Domaine $domaine
     * @return bool
     */
    public function hasDomaine(Domaine $domaine) : bool
    {
        foreach ($this->domaines as $domaine_) {
            if ($domaine_ === $domaine) return true;
        }
        return false;
    }

    public function __toString()
    {
        return $this->getLibelle();
    }
}