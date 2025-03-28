<?php

namespace Application\Entity\Db;

class SpecificitePoste
{

    private ?int $id = null;
    private ?FichePoste $fiche = null;
    private ?string $specificite = null;
    private ?string $encadrement = null;
    private ?string $relationsInternes = null;
    private ?string $relationsExternes = null;
    private ?string $contraintes = null;
    private ?string $moyens = null;
    private ?string $formations = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFiche(): ?FichePoste
    {
        return $this->fiche;
    }

    public function setFiche(?FichePoste $fiche): void
    {
        $this->fiche = $fiche;
    }

    public function getSpecificite(): ?string
    {
        return $this->specificite;
    }

    public function setSpecificite(?string $specificite): void
    {
        $this->specificite = $specificite;
    }

    public function getEncadrement(): ?string
    {
        return $this->encadrement;
    }

    public function setEncadrement(?string $encadrement): void
    {
        $this->encadrement = $encadrement;
    }

    public function getRelationsInternes(): ?string
    {
        return $this->relationsInternes;
    }

    public function setRelationsInternes(?string $relationsInternes): void
    {
        $this->relationsInternes = $relationsInternes;
    }

    public function getRelationsExternes(): ?string
    {
        return $this->relationsExternes;
    }

    public function setRelationsExternes(?string $relationsExternes): void
    {
        $this->relationsExternes = $relationsExternes;
    }

    public function getContraintes(): ?string
    {
        return $this->contraintes;
    }

    public function setContraintes(?string $contraintes): void
    {
        $this->contraintes = $contraintes;
    }

    public function getMoyens(): ?string
    {
        return $this->moyens;
    }

    public function setMoyens(?string $moyens): void
    {
        $this->moyens = $moyens;
    }

    public function getFormations(): ?string
    {
        return $this->formations;
    }

    public function setFormations(?string $formations)
    {
        $this->formations = $formations;
    }

    /**
     * @return SpecificiteActivite[]
     */
    public function getActivites(): array
    {
        $result = [];
//        $activites = ($this->activites === null) ? [] : $this->activites->toArray();
//        foreach ($activites as $activite) {
//            $result[$activite->getId()] = $activite;
//        }
        return $result;
    }

    /** @return SpecificitePoste */
    public function clone_it(): SpecificitePoste
    {
        $result = new SpecificitePoste();
        $result->setSpecificite($this->getSpecificite());
        $result->setEncadrement($this->getEncadrement());
        $result->setRelationsInternes($this->getRelationsInternes());
        $result->setRelationsExternes($this->getRelationsExternes());
        $result->setContraintes($this->getContraintes());
        $result->setMoyens($this->getMoyens());
        $result->setFormations($this->getFormations());

        return $result;
    }


}