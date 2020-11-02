<?php

namespace Formation\Service\FormationInstanceFrais;

trait FormationInstanceFraisServiceAwareTrait
{

    /** @var FormationInstanceFraisService */
    private $formationInstanceFraisService;

    /**
     * @return FormationInstanceFraisService
     */
    public function getFormationInstanceFraisService(): FormationInstanceFraisService
    {
        return $this->formationInstanceFraisService;
    }

    /**
     * @param FormationInstanceFraisService $formationInstanceFraisService
     * @return FormationInstanceFraisService
     */
    public function setFormationInstanceFraisService(FormationInstanceFraisService $formationInstanceFraisService): FormationInstanceFraisService
    {
        $this->formationInstanceFraisService = $formationInstanceFraisService;
        return $this->formationInstanceFraisService;
    }


}