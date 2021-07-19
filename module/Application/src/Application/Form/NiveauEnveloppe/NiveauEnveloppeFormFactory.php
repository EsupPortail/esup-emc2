<?php

namespace Application\Form\NiveauEnveloppe;

use Application\Service\Niveau\NiveauService;
use Interop\Container\ContainerInterface;

class NiveauEnveloppeFormFactory {

    /**
     * @param ContainerInterface $container
     * @return NiveauEnveloppeForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /** @var NiveauService $niveauService */
        $niveauService = $container->get(NiveauService::class);

        /** @var NiveauEnveloppeHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(NiveauEnveloppeHydrator::class);

        $form = new NiveauEnveloppeForm();
        $form->setNiveauService($niveauService);
        $form->setHydrator($hydrator);

        $array = [];
        $niveaux = $niveauService->getNiveaux();
        foreach ($niveaux as $niveau) $array[$niveau->getId()] = $niveau;

        $form->niveaux = $array;

        return $form;
    }
}