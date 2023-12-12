<?php

namespace Formation\Controller;

use Formation\Form\Inscription\InscriptionForm;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\Inscription\InscriptionService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class InscriptionControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return InscriptionController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): InscriptionController
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var InscriptionService $inscriptionService
         * @var InscriptionForm $inscriptionForm
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $inscriptionService = $container->get(InscriptionService::class);
        $inscriptionForm = $container->get('FormElementManager')->get(InscriptionForm::class);

        $controller = new InscriptionController();
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setInscriptionService($inscriptionService);
        $controller->setInscriptionForm($inscriptionForm);
        return $controller;
    }
}