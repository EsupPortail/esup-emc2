<?php

namespace Application\Controller;

use Application\Form\AgentAccompagnement\AgentAccompagnementForm;
use Application\Service\Agent\AgentService;
use Application\Service\AgentAccompagnement\AgentAccompagnementService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatCategorie\EtatCategorieService;
use UnicaenEtat\Service\EtatType\EtatTypeService;

class AgentAccompagnementControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return AgentAccompagnementController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): AgentAccompagnementController
    {
        /**
         * @var AgentService $agentService
         * @var AgentAccompagnementService $agentAccompagnementService
         * @var AgentAccompagnementForm $agentAccompagnementForm
         * @var EtatCategorieService $etatCategorieService
         * @var EtatTypeService $etatTypeService
         */
        $agentService = $container->get(AgentService::class);
        $agentAccompagnementService = $container->get(AgentAccompagnementService::class);
        $etatCategorieService = $container->get(EtatCategorieService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $agentAccompagnementForm = $container->get('FormElementManager')->get(AgentAccompagnementForm::class);

        $controller = new AgentAccompagnementController();
        $controller->setAgentService($agentService);
        $controller->setAgentAccompagnementService($agentAccompagnementService);
        $controller->setEtatCategorieService($etatCategorieService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setAgentAccompagnementForm($agentAccompagnementForm);

        return $controller;
    }
}