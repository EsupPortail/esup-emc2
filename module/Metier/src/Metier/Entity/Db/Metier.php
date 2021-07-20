<?php

namespace Metier\Entity\Db;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Categorie;
use Application\Entity\Db\FicheMetier;
use Application\Entity\Db\NiveauEnveloppe;
use Doctrine\Common\Collections\ArrayCollection;
use Metier\Service\Metier\MetierService;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Metier implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var string */
    private $libelleFeminin;
    /** @var string */
    private $libelleMasculin;
    /** @var Categorie */
    private $categorie;
    /** @var integer */
    private $niveau;
    /** @var NiveauEnveloppe|null */
    private $niveaux;

    /** @var ArrayCollection (Domaine) */
    private  $domaines;
    /** @var ArrayCollection (MetierReference) */
    private $references;
    /** @var ArrayCollection (FicheMetier) */
    private $fichesMetiers;

    public function __construct()
    {
        $this->references = new ArrayCollection();
        $this->fichesMetiers = new ArrayCollection();
        $this->domaines = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId() : int
    {
        return $this->id;
    }

    /**
     * @param bool $inclusif
     * @return string
     */
    public function getLibelle(bool $inclusif = true) : string
    {
        if ($inclusif === false) return $this->libelle;
        if ($this->libelleFeminin !== null AND $this->libelleMasculin !== null) {
            $inclusif = MetierService::computeEcritureInclusive($this->getLibelleFeminin(), $this->getLibelleMasculin());
            return $inclusif;
        }
        return $this->libelle;
    }

    /**
     * @param string|null $libelle
     * @return Metier
     */
    public function setLibelle(?string $libelle) : Metier
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLibelleFeminin(): ?string
    {
        return ($this->libelleFeminin)?:$this->libelle;
    }

    /**
     * @param string|null $libelleFeminin
     * @return Metier
     */
    public function setLibelleFeminin(?string $libelleFeminin): Metier
    {
        $this->libelleFeminin = $libelleFeminin;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getLibelleMasculin(): ?string
    {
        return ($this->libelleMasculin)?:$this->libelle;
    }

    /**
     * @param string|null $libelleMasculin
     * @return Metier
     */
    public function setLibelleMasculin(?string $libelleMasculin): Metier
    {
        $this->libelleMasculin = $libelleMasculin;
        return $this;
    }


    public function __toString()
    {
        return $this->getLibelle();
    }

    /**
     * @return FicheMetier[]
     */
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

    /**
     * @return Domaine[]
     */
    public function getDomaines() : array
    {
        $domaines =  $this->domaines->toArray();
        usort($domaines, function (Domaine $a, Domaine $b) { return $a->getLibelle() > $b->getLibelle();});
        return $domaines;
    }

    public function clearDomaines() : Metier
    {
        $this->domaines->clear();
        return $this;
    }

    public function addDomaine(Domaine $domaine)
    {
        $this->domaines->add($domaine);
    }

    /**
     * @return Categorie|null
     */
    public function getCategorie() : ?Categorie
    {
        return $this->categorie;
    }

    /**
     * @param Categorie|null $categorie
     * @return Metier
     */
    public function setCategorie(?Categorie $categorie) : Metier
    {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getNiveau() : ?int
    {
        return $this->niveau;
    }

    /**
     * @param int|null $niveau
     * @return Metier
     */
    public function setNiveau(?int $niveau) : Metier
    {
        $this->niveau = $niveau;
        return $this;
    }

    /**
     * @return NiveauEnveloppe |null
     */
    public function getNiveaux(): ?NiveauEnveloppe
    {
        return $this->niveaux;
    }

    /**
     * @param NiveauEnveloppe |null $niveaux
     * @return Metier
     */
    public function setNiveaux(?NiveauEnveloppe $niveaux): Metier
    {
        $this->niveaux = $niveaux;
        return $this;
    }

    /**
     * @return string
     */
    public function generateTooltip() : string
    {
        $html  = '';
        /** ligne sur le metier **/
        $html .= '<span class="highlight metier">'.htmlentities($this->getLibelle(),ENT_QUOTES).'</span><br/>';
        /** lignes sur les domaines du metier **/
        $html .= '<strong>Domaines</strong> :';
        $html .= '<ul>';
        foreach ($this->getDomaines() as $domaine) {
            $html .= '<li>'.$domaine->getLibelle().'</li>';
        }
        $html .= '</ul>';
        /** ligne sur les refs */
        $html .= '<strong>Références</strong> :';
        foreach ($this->getReferences() as $reference) {
            $html .= '<span class="badge" style="margin:0.25rem;">'.$reference->getReferentiel()->getLibelleCourt().' - '.$reference->getCode().'</span><br/>';
        }
        return '<span style="text-align:left;">'.$html.'</span>';
    }

    /** Fonctions pour affichage **************************************************************************************/

    public function getReferencesAffichage() : string
    {
        $texte = "";
        /** @var Reference $reference */
        foreach ($this->getReferences() as $reference) {
            $texte .= "<a href='". $reference->getUrl()."'>";
            $texte .= $reference->getReferentiel()->getLibelleCourt().' - '.$reference->getCode();
            $texte .= "</a>";
        }
        return $texte;
    }

    public function getDomaineAndFamille() : string
    {
        $texte = "";
        /** @var Domaine $domaine */
        $texte .= "<ul>";
        foreach ($this->domaines as $domaine) {
            $texte .= "<li>" . $domaine->getLibelle() . " / " . $domaine->getFamille()->getLibelle() . "</li>";
        }
        $texte .= "</ul>";
        return $texte;
    }

    public function getLibelleGenre(?Agent $agent) : string
    {
        if ($agent === null) return $this->getLibelle();
        if ($agent->isHomme() AND $this->libelleMasculin !== null) return $this->getLibelleMasculin();
        if ($agent->isFemme() AND $this->libelleFeminin !== null) return $this->getLibelleFeminin();
        return $this->getLibelle();
    }
}