<?php

namespace Formation\Form\Demande2Formation;

use Doctrine\ORM\Tools\Event\GenerateSchemaEventArgs;
use Formation\Service\Axe\AxeService;
use Formation\Service\FormationGroupe\FormationGroupeService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class Demande2FormationFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return Demande2FormationForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): Demande2FormationForm
    {
        /**
         * @var AxeService $axeService
         * @var FormationGroupeService $formationGroupeService
         * @var Demande2FormationHydrator $hydrator
         */
        $axeService = $container->get(AxeService::class);
        $formationGroupeService = $container->get(FormationGroupeService::class);
        $hydrator = $container->get('HydratorManager')->get(Demande2FormationHydrator::class);

        $form = new Demande2FormationForm();
        $form->setAxeService($axeService);
        $form->setFormationGroupeService($formationGroupeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}