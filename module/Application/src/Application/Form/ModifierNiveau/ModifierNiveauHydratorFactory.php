<?php

namespace Application\Form\ModifierNiveau;

use Application\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;

class ModifierNiveauHydratorFactory
{
    /**
     * @param ContainerInterface $container
     * @return ModifierNiveauHydrator
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var NiveauService $niveauService */
        $niveauService = $container->get(NiveauService::class);

        /** @var ModifierNiveauHydrator $hydrator */
        $hydrator = new ModifierNiveauHydrator();
        $hydrator->setNiveauService($niveauService);
        return $hydrator;
    }
}