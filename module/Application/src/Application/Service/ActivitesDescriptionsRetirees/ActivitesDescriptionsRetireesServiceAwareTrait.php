<?php

namespace Application\Service\ActivitesDescriptionsRetirees;

trait ActivitesDescriptionsRetireesServiceAwareTrait {

    /** @var ActivitesDescriptionsRetireesService */
    private $activitesDescriptionsRetireesService;

    /**
     * @return ActivitesDescriptionsRetireesService
     */
    public function getActivitesDescriptionsRetireesService()
    {
        return $this->activitesDescriptionsRetireesService;
    }

    /**
     * @param ActivitesDescriptionsRetireesService $activitesDescriptionsRetireesService
     * @return ActivitesDescriptionsRetireesService
     */
    public function setActivitesDescriptionsRetireesService($activitesDescriptionsRetireesService)
    {
        $this->activitesDescriptionsRetireesService = $activitesDescriptionsRetireesService;
        return $this->activitesDescriptionsRetireesService;
    }


}