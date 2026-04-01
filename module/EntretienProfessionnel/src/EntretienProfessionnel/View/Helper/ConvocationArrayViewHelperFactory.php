<?php

namespace EntretienProfessionnel\View\Helper;

use Agent\Service\AgentAffectation\AgentAffectationService;
use Agent\Service\AgentGrade\AgentGradeService;
use Application\Entity\Db\Agent;
use Application\Service\AgentAutorite\AgentAutoriteService;
use Application\Service\AgentSuperieur\AgentSuperieurService;
use EntretienProfessionnel\Entity\Db\Campagne;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

class ConvocationArrayViewHelperFactory extends AbstractHelper
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ConvocationArrayViewHelper
    {
        /**
         * @var AgentAffectationService $agentAffectationService
         * @var AgentAutoriteService $agentAutoriteService
         * @var AgentGradeService $agentGradeService
         * @var AgentSuperieurService $agentSuperieurService
         */
        $agentAffectationService = $container->get(AgentAffectationService::class);
        $agentAutoriteService = $container->get(AgentAutoriteService::class);
        $agentGradeService = $container->get(AgentGradeService::class);
        $agentSuperieurService = $container->get(AgentSuperieurService::class);

        $helper = new ConvocationArrayViewHelper();
        $helper->setAgentAffectationService($agentAffectationService);
        $helper->setAgentAutoriteService($agentAutoriteService);
        $helper->setAgentGradeService($agentGradeService);
        $helper->setAgentSuperieurService($agentSuperieurService);

        return $helper;
    }
}