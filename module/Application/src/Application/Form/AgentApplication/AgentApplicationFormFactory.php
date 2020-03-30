<?php

namespace Application\Form\AgentApplication;

use Application\Service\Application\ApplicationService;
use Interop\Container\ContainerInterface;

class AgentApplicationFormFactory {

    /**
     * @param ContainerInterface $container
     * @return AgentApplicationForm
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var ApplicationService $applicationService
         */
        $applicationService = $container->get(ApplicationService::class);

        /** @var AgentApplicationHydrator $hydrator */
        $hydrator = $container->get('HydratorManager')->get(AgentApplicationHydrator::class);

        /** @var AgentApplicationForm $form */
        $form = new AgentApplicationForm();
        $form->setApplicationService($applicationService);
        $form->setHydrator($hydrator);
        return $form;
    }
}