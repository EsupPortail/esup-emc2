<?php

namespace Application\Form\MetierReference;

use Application\Service\Metier\MetierService;
use Application\Service\MetierReferentiel\MetierReferentielService;
use Interop\Container\ContainerInterface;

class MetierReferenceFormFactory {

    /**
     * @param ContainerInterface $container
     * @return MetierReferenceForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var MetierService $metierService
         * @var MetierReferentielService $referentielService
         */
        $metierService = $container->get(MetierService::class);
        $referentielService = $container->get(MetierReferentielService::class);

        /** @var MetierReferenceHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(MetierReferenceHydrator::class);

        $form = new MetierReferenceForm();
        $form->setMetierService($metierService);
        $form->setMetierReferentielService($referentielService);
        $form->setHydrator($hydrator);
        return $form;
    }
}