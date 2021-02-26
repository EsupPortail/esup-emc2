<?php

namespace UnicaenParametre\Form\Parametre;

use Interop\Container\ContainerInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class ParametreFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ParametreService $parametreService
         * @var ParametreHydrator $hydrator
         */
        $parametreService = $container->get(ParametreService::class);
        $hydrator = $container->get('HydratorManager')->get(ParametreHydrator::class);

        $form = new ParametreForm();
        $form->setParametreService($parametreService);
        $form->setHydrator($hydrator);
        return $form;
    }
}