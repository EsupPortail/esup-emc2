<?php

namespace Metier\Form\MetierNiveau;

use Application\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;

class MetierNiveauFormFactory {

    /**
     * @param ContainerInterface $container
     * @return MetierNiveauForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var NiveauService $niveauService */
        $niveauService = $container->get(NiveauService::class);

        /** @var MetierNiveauHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(MetierNiveauHydrator::class);

        $form = new MetierNiveauForm();
        $form->setNiveauService($niveauService);
        $form->setHydrator($hydrator);

        $array = [];
        $niveaux = $niveauService->getNiveaux();
        foreach ($niveaux as $niveau) $array[$niveau->getId()] = $niveau;

        $form->niveaux = $array;

        return $form;
    }
}