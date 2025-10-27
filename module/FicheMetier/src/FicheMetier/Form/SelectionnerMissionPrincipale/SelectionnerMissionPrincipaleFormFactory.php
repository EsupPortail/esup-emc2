<?php

namespace FicheMetier\Form\SelectionnerMissionPrincipale;

use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerMissionPrincipaleFormFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionnerMissionPrincipaleForm
    {
        /**
         * @var MissionPrincipaleService $missionPrincipaleService
         * @var SelectionnerMissionPrincipaleHydrator $hydrator
         */
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $hydrator = $container->get('HydratorManager')->get(SelectionnerMissionPrincipaleHydrator::class);

        $form = new SelectionnerMissionPrincipaleForm();
        $form->setMissionPrincipaleService($missionPrincipaleService);
        $form->setHydrator($hydrator);
        return $form;
    }
}