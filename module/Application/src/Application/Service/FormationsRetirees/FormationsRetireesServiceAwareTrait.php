<?php

namespace Application\Service\FormationsRetirees;

trait FormationsRetireesServiceAwareTrait {

    /** @var FormationsRetireesService $formationsConserveesService */
    private $formationsConserveesService;

    /**
     * @return FormationsRetireesService
     */
    public function getFormationsRetireesService()
    {
        return $this->formationsConserveesService;
    }

    /**
     * @param FormationsRetireesService $formationsConserveesService
     * @return FormationsRetireesService
     */
    public function setFormationsRetireesService($formationsConserveesService)
    {
        $this->formationsConserveesService = $formationsConserveesService;
        return $this->formationsConserveesService;
    }

}