<?php

namespace Application\Form\FicheProfil;

trait FicheProfilFormAwareTrait {

    /** @var FicheProfilForm */
    private $ficheProfilForm;

    /**
     * @return FicheProfilForm
     */
    public function getFicheProfilForm(): FicheProfilForm
    {
        return $this->ficheProfilForm;
    }

    /**
     * @param FicheProfilForm $ficheProfilForm
     * @return FicheProfilForm
     */
    public function setFicheProfilForm(FicheProfilForm $ficheProfilForm): FicheProfilForm
    {
        $this->ficheProfilForm = $ficheProfilForm;
        return $this->ficheProfilForm;
    }

}