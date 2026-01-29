<?php

namespace Referentiel\Entity\Db;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Element\Entity\Db\Competence;
use FicheMetier\Entity\Db\Activite;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\Mission;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareInterface;
use UnicaenUtilisateur\Entity\Db\HistoriqueAwareTrait;

class Referentiel implements HistoriqueAwareInterface
{
    use HistoriqueAwareTrait;

    private ?int $id = null;
    private ?string $libelleCourt = null;
    private ?string $libelleLong = null;
    private ?string $couleur = null;
    private ?string $description = null;

    private Collection $activites;
    private Collection $missions;
    private Collection $competences;
    private Collection $fichesmetiers;

    public function __construct()
    {
        $this->activites = new ArrayCollection();
        $this->missions = new ArrayCollection();
        $this->competences = new ArrayCollection();
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

    public function getLibelleCourt(): ?string
    {
        return $this->libelleCourt;
    }

    public function setLibelleCourt(?string $libelleCourt): void
    {
        $this->libelleCourt = $libelleCourt;
    }

    public function getLibelleLong(): ?string
    {
        return $this->libelleLong;
    }

    public function setLibelleLong(?string $libelleLong): void
    {
        $this->libelleLong = $libelleLong;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(?string $couleur): void
    {
        $this->couleur = $couleur;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /** @return Activite[] */
    public function getActivites(): array {
        return $this->activites->toArray();
    }

    /** @return Mission[] */
    public function getMissions(): array {
        return $this->missions->toArray();
    }

    /** @return Competence[] */
    public function getCompetences(): array {
        return $this->competences->toArray();
    }

    /** @return FicheMetier[] */
    public function getFichesMetiers(): array {
        return $this->fichesmetiers->toArray();
    }

    /** FACADE **************************************/

    public function printLabel(): string
    {
        return "<span class='badge' style='background:".$this->getCouleur()."'>".$this->getLibelleCourt()."</span> ". $this->getLibelleLong();
    }

    /** Les modes sont : modal, lien **/
    public function printReference(?string $mode = null, ?string $url = null, bool $isAllowed = true): string
    {
        $label  = ($this->getLibelleCourt()??"Aucun référentiel");
        $span = "<span class='badge' style='background:".($this->getCouleur()??"grey")."'>" . $label . "</span>";

        if ($isAllowed AND $mode !== null) {
            if ($mode === 'modal') {
                $texte  = "<a href='" . $url . "' class='ajax-modal'>";
                $texte .= $span;
                $texte .= "</a>";
                return $texte;
            }
            if ($mode === 'lien') {
                $texte  = "<a href='" . $url . "' target='_blank'> ";
                $texte .= $span;
                $texte .= "</a>";
                return $texte;
            }
        }
        return $span;
    }
}
