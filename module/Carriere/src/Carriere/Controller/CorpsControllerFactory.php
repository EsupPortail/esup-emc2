<?php

namespace Carriere\Controller;

use Agent\Service\Agent\AgentService;
use Agent\Service\AgentGrade\AgentGradeService;
use Application\Service\Util\UtilService;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeForm;
use Carriere\Service\Corps\CorpsService;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeService;
use Interop\Container\ContainerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenUtilisateur\Service\User\UserService;

class CorpsControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return CorpsController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): CorpsController
    {
        /**
         * @var AgentService $agentService
         * @var AgentGradeService $agentGradeService
         * @var CorpsService $corpsService
         * @var NiveauEnveloppeService $niveauEnveloppeService
         * @var ParametreService $parametreService
         * @var UserService $userService
         * @var UtilService $utilService
         */
        $agentService = $container->get(AgentService::class);
        $agentGradeService = $container->get(AgentGradeService::class);
        $corpsService = $container->get(CorpsService::class);
        $niveauEnveloppeService = $container->get(NiveauEnveloppeService::class);
        $parametreService = $container->get(ParametreService::class);
        $userService = $container->get(UserService::class);
        $utilService = $container->get(UtilService::class);

        /**
         * @var NiveauEnveloppeForm $niveauEnveloppeForm
         */
        $niveauEnveloppeForm = $container->get('FormElementManager')->get(NiveauEnveloppeForm::class);

        $controller = new CorpsController();
        $controller->setAgentService($agentService);
        $controller->setAgentGradeService($agentGradeService);
        $controller->setCorpsService($corpsService);
        $controller->setNiveauEnveloppeService($niveauEnveloppeService);
        $controller->setParametreService($parametreService);
        $controller->setUserService($userService);
        $controller->setUtilService($utilService);


        $controller->setNiveauEnveloppeForm($niveauEnveloppeForm);
        return $controller;
    }
}