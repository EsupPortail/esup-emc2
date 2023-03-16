<?php

namespace Metier\Form\Reference;

use Interop\Container\ContainerInterface;
use Metier\Service\Metier\MetierService;
use Metier\Service\Referentiel\ReferentielService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class ReferenceFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ReferenceForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : ReferenceForm
    {
        /**
         * @var MetierService $metierService
         * @var ReferentielService $referentielService
         */
        $metierService = $container->get(MetierService::class);
        $referentielService = $container->get(ReferentielService::class);

        /** @var ReferenceHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(ReferenceHydrator::class);

        $form = new ReferenceForm();
        $form->setMetierService($metierService);
        $form->setReferentielService($referentielService);
        $form->setHydrator($hydrator);
        return $form;
    }
}