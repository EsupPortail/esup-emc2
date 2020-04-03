<?php

namespace Application\Form\EntretienProfessionnel;

use Application\Controller\AgentController;
use Application\Service\Agent\AgentService;
use Interop\Container\ContainerInterface;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenUtilisateur\Service\User\UserService;
use Zend\View\HelperPluginManager;

class EntretienProfessionnelFormFactory {

    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var RoleService $roleService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $roleService = $container->get(RoleService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var EntretienProfessionnelHydrator $hydrator
         */
        $hydrator = $container->get('HydratorManager')->get(EntretienProfessionnelHydrator::class);

        /** @var HelperPluginManager $pluginManager */
        $pluginManager = $container->get('ViewHelperManager');
        /** @var \Zend\View\Helper\Url $urlManager */
        $urlManager = $pluginManager->get('Url');
        /** @see AgentController::rechercherAction() */
        $urlAgent       =  $urlManager->__invoke('agent/rechercher', [], [], true);
        /** @see AgentController::rechercherResponsableAction() */
        $urlReponsable  =  $urlManager->__invoke('agent/rechercher-responsable', [], [], true);

        /**
         * @var EntretienProfessionnelForm $form
         */
        $form = new EntretienProfessionnelForm();
        $form->setUrlAgent($urlAgent);
        $form->setUrlResponsable($urlReponsable);
        $form->setHydrator($hydrator);
        $form->init();

        return $form;
    }
}
