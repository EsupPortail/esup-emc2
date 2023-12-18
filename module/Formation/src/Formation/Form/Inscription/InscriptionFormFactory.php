<?php

namespace Formation\Form\Inscription;

use Laminas\View\Renderer\PhpRenderer;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class InscriptionFormFactory {

    /**
     * @param ContainerInterface $container
     * @return InscriptionForm
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : InscriptionForm
    {
        /**
         * @var InscriptionHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(InscriptionHydrator::class);

        /* @var PhpRenderer $renderer  */
        $renderer = $container->get('ViewRenderer');

        $form = new InscriptionForm();
        $form->setHydrator($hydrator);

        $form->sessionUrl = $renderer->url('formation-instance/rechercher', [], [], true);
        $form->agentUrl = $renderer->url('agent/rechercher-large', [], [], true);
        $form->stagiaireUrl = $renderer->url('stagiaire-externe/rechercher', [], [], true);
        return $form;
    }
}