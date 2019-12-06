<?php

namespace Application\Service\FormationsConservees;

trait FormationsConserveesServiceAwareTrait {

    /** @var FormationsConserveesService $formationsConserveesService */
    private $formationsConserveesService;

    /**
     * @return FormationsConserveesService
     */
    public function getFormationsConserveesService()
    {
        return $this->formationsConserveesService;
    }

    /**
     * @param FormationsConserveesService $formationsConserveesService
     * @return FormationsConserveesService
     */
    public function setFormationsConserveesService($formationsConserveesService)
    {
        $this->formationsConserveesService = $formationsConserveesService;
        return $this->formationsConserveesService;
    }

}