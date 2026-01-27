<?php

namespace FicheMetier\Form\SelectionnerActivites;

use FicheMetier\Service\Activite\ActiviteService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class SelectionnerActivitesFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): SelectionnerActivitesForm
    {
        /**
         * @var ActiviteService $activiteService
         * @var SelectionnerActivitesHydrator $hydrator
         */
        $activiteService = $container->get(ActiviteService::class);
        $hydrator = $container->get('HydratorManager')->get(SelectionnerActivitesHydrator::class);

        $form = new SelectionnerActivitesForm();
        $form->setActiviteService($activiteService);
        $form->setHydrator($hydrator);
        return $form;
    }
}
