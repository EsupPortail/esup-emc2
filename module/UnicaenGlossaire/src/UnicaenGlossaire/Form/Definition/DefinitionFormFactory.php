<?php

namespace UnicaenGlossaire\Form\Definition;

use Interop\Container\ContainerInterface;
use UnicaenGlossaire\Service\Definition\DefinitionService;

class DefinitionFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var DefinitionService $definitionService
         * @var DefinitionHydrator $hydrator
         */
        $definitionService = $container->get(DefinitionService::class);
        $hydrator = $container->get('HydratorManager')->get(DefinitionHydrator::class);

        $form = new DefinitionForm();
        $form->setDefinitionService($definitionService);
        $form->setHydrator($hydrator);
        return $form;
    }
}