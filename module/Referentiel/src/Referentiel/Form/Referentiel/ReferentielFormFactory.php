<?php

namespace Referentiel\Form\Referentiel;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Referentiel\Service\Referentiel\ReferentielService;

class ReferentielFormFactory
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ReferentielForm
    {
        /**
         * @var ReferentielService $referentielService
         * @var ReferentielHydrator $hydrator
         */
        $referentielService = $container->get(ReferentielService::class);
        $hydrator = $container->get('HydratorManager')->get(ReferentielHydrator::class);

        $form = new ReferentielForm();
        $form->setReferentielService($referentielService);
        $form->setHydrator($hydrator);
        return $form;
    }
}
