<?php

namespace Formation\Entity\Db;

class LAGAFStagiaire {

    private ?int $nStagiaire = null;
    private ?string $nom = null;
    private ?string $prenom = null;
    private ?int $annee = null;
    private ?string $harp_id = null;
    private ?string $octopus_id = null;


    /**
     * @return int|null
     */
    public function getNStagiaire(): ?int
    {
        return $this->nStagiaire;
    }

    /**
     * @param int|null $nStagiaire
     * @return LAGAFStagiaire
     */
    public function setNStagiaire(?int $nStagiaire): LAGAFStagiaire
    {
        $this->nStagiaire = $nStagiaire;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * @param string|null $nom
     * @return LAGAFStagiaire
     */
    public function setNom(?string $nom): LAGAFStagiaire
    {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    /**
     * @param string|null $prenom
     * @return LAGAFStagiaire
     */
    public function setPrenom(?string $prenom): LAGAFStagiaire
    {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return int|null
     */
    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    /**
     * @param int|null $annee
     * @return LAGAFStagiaire
     */
    public function setAnnee(?int $annee): LAGAFStagiaire
    {
        $this->annee = $annee;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getHarpId(): ?string
    {
        return $this->harp_id;
    }

    /**
     * @param string|null $harp_id
     * @return LAGAFStagiaire
     */
    public function setHarpId(?string $harp_id): LAGAFStagiaire
    {
        $this->harp_id = $harp_id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getOctopusId(): ?string
    {
        return $this->octopus_id;
    }

    /**
     * @param string|null $octopus_id
     * @return LAGAFStagiaire
     */
    public function setOctopusId(?string $octopus_id): LAGAFStagiaire
    {
        $this->octopus_id = $octopus_id;
        return $this;
    }


}