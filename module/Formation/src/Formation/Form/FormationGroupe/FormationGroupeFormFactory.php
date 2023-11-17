<?php

namespace Formation\Form\FormationGroupe;

use Formation\Service\Axe\AxeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormationGroupeFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationGroupeForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): FormationGroupeForm
    {
        /**
         * @var AxeService $axeService
         * @var FormationGroupeHydrator $hydrator
         **/
        $axeService = $container->get(AxeService::class);
        $hydrator = $container->get('HydratorManager')->get(FormationGroupeHydrator::class);

        /** @var FormationGroupeForm $form */
        $form = new FormationGroupeForm();
        $form->setAxeService($axeService);
        $form->setHydrator($hydrator);
        return $form;
    }
}