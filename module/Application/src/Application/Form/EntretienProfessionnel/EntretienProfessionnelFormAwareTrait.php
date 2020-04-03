<?php

namespace Application\Form\EntretienProfessionnel;

trait EntretienProfessionnelFormAwareTrait {

    /** @var EntretienProfessionnelForm $entretienProfessionnelForm */
    private $entretienProfessionnelForm;

    /**
     * @return EntretienProfessionnelForm
     */
    public function getEntretienProfessionnelForm()
    {
        return $this->entretienProfessionnelForm;
    }

    /**
     * @param EntretienProfessionnelForm $entretienProfessionnelForm
     * @return EntretienProfessionnelForm
     */
    public function setEntretienProfessionnelForm($entretienProfessionnelForm)
    {
        $this->entretienProfessionnelForm = $entretienProfessionnelForm;
        return $this->entretienProfessionnelForm;
    }


}
