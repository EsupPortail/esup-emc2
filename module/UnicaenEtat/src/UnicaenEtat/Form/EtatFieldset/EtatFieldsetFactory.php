<?php

namespace UnicaenEtat\Form\EtatFieldset;

use Interop\Container\ContainerInterface;
use UnicaenEtat\Service\Etat\EtatService;

class EtatFieldsetFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var EtatService $etatService
         */
        $etatService = $container->get(EtatService::class);

        $fieldset = new EtatFieldset();
        $fieldset->setEtatService($etatService);
        return $fieldset;
    }
}