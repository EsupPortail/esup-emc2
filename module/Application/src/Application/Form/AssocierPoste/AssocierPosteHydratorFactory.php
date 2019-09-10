<?php

namespace Application\Form\AssocierPoste;

use Application\Service\Poste\PosteService;
use Interop\Container\ContainerInterface;

class AssocierPosteHydratorFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var PosteService $posteService */
        $posteService = $container->get(PosteService::class);

        $hydrator = new AssocierPosteHydrator();
        $hydrator->setPosteService($posteService);

        return $hydrator;
    }

}