<?php

namespace Application\Form\FonctionActivite;

use Application\Service\Fonction\FonctionService;
use Interop\Container\ContainerInterface;

class FonctionActiviteHydratorFactory
{
    /**
     * @param ContainerInterface $container
     * @return FonctionActiviteHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var FonctionService $fonctionService */
        $fonctionService = $container->get(FonctionService::class);

        /** @var FonctionActiviteHydrator $hydrator */
        $hydrator = new FonctionActiviteHydrator();
        $hydrator->setFonctionService($fonctionService);
        return $hydrator;
    }
}