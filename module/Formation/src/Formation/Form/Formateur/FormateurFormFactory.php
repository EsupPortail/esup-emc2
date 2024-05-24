<?php

namespace Formation\Form\Formateur;

use Formation\Service\Formateur\FormateurService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormateurFormFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormateurForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FormateurForm
    {
        /**
         * @var FormateurService $formateurService
         * @var FormateurHydrator $hydrator
         */
        $formateurService = $container->get(FormateurService::class);
        $hydrator = $container->get('HydratorManager')->get(FormateurHydrator::class);

        $form = new FormateurForm();
        $form->setFormateurService($formateurService);
        $form->setHydrator($hydrator);
        return $form;
    }
}