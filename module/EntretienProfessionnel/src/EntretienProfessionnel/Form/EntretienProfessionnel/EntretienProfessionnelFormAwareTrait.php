<?php

namespace EntretienProfessionnel\Form\EntretienProfessionnel;

trait EntretienProfessionnelFormAwareTrait {

    /** @var EntretienProfessionnelForm $entretienProfessionnelForm */
    private $entretienProfessionnelForm;

    /**
     * @return EntretienProfessionnelForm
     */
    public function getEntretienProfessionnelForm() : EntretienProfessionnelForm
    {
        return $this->entretienProfessionnelForm;
    }

    /**
     * @param EntretienProfessionnelForm $entretienProfessionnelForm
     * @return EntretienProfessionnelForm
     */
    public function setEntretienProfessionnelForm(EntretienProfessionnelForm $entretienProfessionnelForm) : EntretienProfessionnelForm
    {
        $this->entretienProfessionnelForm = $entretienProfessionnelForm;
        return $this->entretienProfessionnelForm;
    }


}
