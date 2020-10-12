<?php

namespace Application\Service\FormationInstance;

trait FormationInstanceFormateurServiceAwareTrait {

    /** @var FormationInstanceFormateurService */
    private $formationInstanceFormateurService;

    /**
     * @return FormationInstanceFormateurService
     */
    public function getFormationInstanceFormateurService(): FormationInstanceFormateurService
    {
        return $this->formationInstanceFormateurService;
    }

    /**
     * @param FormationInstanceFormateurService $formationInstanceFormateurService
     * @return FormationInstanceFormateurService
     */
    public function setFormationInstanceFormateurService(FormationInstanceFormateurService $formationInstanceFormateurService): FormationInstanceFormateurService
    {
        $this->formationInstanceFormateurService = $formationInstanceFormateurService;
        return $this->formationInstanceFormateurService;
    }


}