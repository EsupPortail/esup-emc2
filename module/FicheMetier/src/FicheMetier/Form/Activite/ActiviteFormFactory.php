<?php

namespace FicheMetier\Form\Activite;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Referentiel\Service\Referentiel\ReferentielService;

class ActiviteFormFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ActiviteForm
    {
        /**
         * @var ReferentielService $referentielService
         * @var ActiviteHydrator $hydrator
         */
        $referentielService = $container->get(ReferentielService::class);
        $hydrator = $container->get('HydratorManager')->get(ActiviteHydrator::class);

        $form = new ActiviteForm();
        $form->setReferentielService($referentielService);
        $form->setHydrator($hydrator);
        return $form;
    }
}
