<?php

namespace Application\Form\AgentPPP;

use Interop\Container\ContainerInterface;
use UnicaenEtat\Service\Etat\EtatService;

class AgentPPPFormFactory {

    public function __invoke(ContainerInterface $container) : AgentPPPForm
    {
        /**
         * @var EtatService $etatService
         */
        $etatService = $container->get(EtatService::class);

        /** @var AgentPPPHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AgentPPPHydrator::class);

        $form = new AgentPPPForm();
        $form->setEtatService($etatService);
        $form->setHydrator($hydrator);
        return $form;
    }
}