<?php

namespace Formation\Controller;

use Formation\Form\Formateur\FormateurForm;
use Formation\Service\Formateur\FormateurService;
use Formation\Service\FormationInstance\FormationInstanceService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;

class FormateurControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormateurController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : FormateurController
    {
        /**
         * @var FormationInstanceService $formationInstanceService
         * @var FormateurService $formateurService
         */
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formateurService = $container->get(FormateurService::class);

        /**
         * @var FormateurForm $formateurForm
         */
        $formateurForm = $container->get('FormElementManager')->get(FormateurForm::class);

        $controller = new FormateurController();
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormateurService($formateurService);
        $controller->setFormateurForm($formateurForm);
        return $controller;
    }
}