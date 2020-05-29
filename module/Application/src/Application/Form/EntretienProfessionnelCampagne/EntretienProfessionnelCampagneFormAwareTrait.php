<?php

namespace Application\Form\EntretienProfessionnelCampagne;

trait EntretienProfessionnelCampagneFormAwareTrait {

    /** @var EntretienProfessionnelCampagneForm */
    private $entretienProfessionnelCampagneForm;

    /**
     * @return EntretienProfessionnelCampagneForm
     */
    public function getEntretienProfessionnelCampagneForm()
    {
        return $this->entretienProfessionnelCampagneForm;
    }

    /**
     * @param EntretienProfessionnelCampagneForm $entretienProfessionnelCampagneForm
     * @return EntretienProfessionnelCampagneForm
     */
    public function setEntretienProfessionnelCampagneForm(EntretienProfessionnelCampagneForm $entretienProfessionnelCampagneForm)
    {
        $this->entretienProfessionnelCampagneForm = $entretienProfessionnelCampagneForm;
        return $this->entretienProfessionnelCampagneForm;
    }


}