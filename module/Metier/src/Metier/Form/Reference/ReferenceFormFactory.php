<?php

namespace Metier\Form\Reference;

use Interop\Container\ContainerInterface;
use Metier\Service\Metier\MetierService;
use Metier\Service\Referentiel\ReferentielService;

class ReferenceFormFactory {

    /**
     * @param ContainerInterface $container
     * @return ReferenceForm
     */
    public function __invoke(ContainerInterface $container)
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