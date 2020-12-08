<?php

namespace Application\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use UnicaenUtilisateur\Entity\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\HistoriqueAwareTrait;

class Metier implements HistoriqueAwareInterface {
    use HistoriqueAwareTrait;

    /** @var integer */
    private $id;
    /** @var string */
    private $libelle;
    /** @var Categorie */
    private $categorie;
    /** @var integer */
    private $niveau;

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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getLibelle()
    {
        return $this->libelle;
    }

    /**
     * @param string $libelle
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
    }

    public function __toString()
    {
        return $this->getLibelle();
    }

    /**
     * @return ArrayCollection
     */
    public function getFichesMetiers()
    {
        return $this->fichesMetiers;
    }

    /**
     * @return MetierReference[]
     */
    public function getReferences()
    {
        return $this->references->toArray();
    }

    /**
     * @return Domaine[]
     */
    public function getDomaines()
    {
        $domaines =  $this->domaines->toArray();
        usort($domaines, function (Domaine $a, Domaine $b) { return $a->getLibelle() > $b->getLibelle();});
        return $domaines;
    }

    public function clearDomaines()
    {
        $this->domaines->clear();
    }

    public function addDomaine(Domaine $domaine)
    {
        $this->domaines->add($domaine);
    }

    /**
     * @return Categorie
     */
    public function getCategorie()
    {
        return $this->categorie;
    }

    /**
     * @param Categorie $categorie
     * @return Metier
     */
    public function setCategorie($categorie)
    {
        $this->categorie = $categorie;
        return $this;
    }

    /**
     * @return int
     */
    public function getNiveau()
    {
        return $this->niveau;
    }

    /**
     * @param int $niveau
     * @return Metier
     */
    public function setNiveau($niveau)
    {
        $this->niveau = $niveau;
        return $this;
    }

    /**
     * @return string
     */
    public function generateTooltip()
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
        /** ligne sur l'expertise */
        //TODO statuer sur ce qui est expert ou pas
        /** ligne sur les refs */
        $html .= '<strong>Références</strong> :';
        foreach ($this->getReferences() as $reference) {
            $html .= '<span class="badge" style="margin:0.25rem;">'.$reference->getReferentiel()->getLibelleCourt().' - '.$reference->getCode().'</span><br/>';
        }
        return '<span style="text-align:left;">'.$html.'</span>';
    }

    /** Fonctions pour affichage **************************************************************************************/

    public function getReferencesAffichage()
    {
        $texte = "";
        /** @var MetierReference $reference */
        foreach ($this->getReferences() as $reference) {
            $texte .= "<a href='". $reference->getUrl()."'>";
            $texte .= $reference->getReferentiel()->getLibelleCourt().' - '.$reference->getCode();
            $texte .= "</a>";
        }
        return $texte;
    }
}