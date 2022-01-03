<?php

namespace EntretienProfessionnel\Form\Campagne;

trait CampagneFormAwareTrait {

    /** @var CampagneForm */
    private $campagneForm;

    /**
     * @return CampagneForm
     */
    public function getCampagneForm() : CampagneForm
    {
        return $this->campagneForm;
    }

    /**
     * @param CampagneForm $campagneForm
     * @return CampagneForm
     */
    public function setCampagneForm(CampagneForm $campagneForm) : CampagneForm
    {
        $this->campagneForm = $campagneForm;
        return $this->campagneForm;
    }


}