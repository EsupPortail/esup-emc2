<?php

namespace Carriere\Controller;

use Agent\Service\AgentGrade\AgentGradeService;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeForm;
use Carriere\Service\Categorie\CategorieService;
use Carriere\Service\Corps\CorpsService;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;

class CorpsControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return CorpsController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : CorpsController
    {
        /**
         * @var AgentGradeService $agentGradeService
         * @var CategorieService $categorieService
         * @var CorpsService $corpsService
         * @var NiveauEnveloppeService $niveauEnveloppeService
         * @var ParametreService $parametreService
         */
        $agentGradeService = $container->get(AgentGradeService::class);
        $categorieService = $container->get(CategorieService::class);
        $corpsService = $container->get(CorpsService::class);
        $niveauEnveloppeService = $container->get(NiveauEnveloppeService::class);
        $parametreService = $container->get(ParametreService::class);

        /**
         * @var NiveauEnveloppeForm $niveauEnveloppeForm
         */
        $niveauEnveloppeForm = $container->get('FormElementManager')->get(NiveauEnveloppeForm::class);

        $controller = new CorpsController();
        $controller->setAgentGradeService($agentGradeService);
        $controller->setCategorieService($categorieService);
        $controller->setCorpsService($corpsService);
        $controller->setNiveauEnveloppeService($niveauEnveloppeService);
        $controller->setParametreService($parametreService);
        $controller->setNiveauEnveloppeForm($niveauEnveloppeForm);
        return $controller;
    }
}