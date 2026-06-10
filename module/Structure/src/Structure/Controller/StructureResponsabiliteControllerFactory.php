<?php

namespace Structure\Controller;


use Agent\Service\Agent\AgentService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Structure\Form\Responsabilite\ResponsabiliteForm;
use Structure\Service\Structure\StructureService;
use Structure\Service\StructureGestionnaire\StructureGestionnaireService;
use Structure\Service\StructureResponsable\StructureResponsableService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenUtilisateur\Service\Role\RoleService;

class StructureResponsabiliteControllerFactory
{

    /**
     * @throws NotFoundExceptionInterface
     * @throws ContainerExceptionInterface
     */
    public function __invoke(ContainerInterface $container): StructureResponsabiliteController
    {
        /**
         * @var ParametreService $parametreService
         * @var RoleService $roleService
         * @var StructureService $structureService
         * @var StructureGestionnaireService $structureGestionnaireService
         * @var StructureResponsableService $structureResponsableService
         */
        $parametreService = $container->get(ParametreService::class);
        $roleService = $container->get(RoleService::class);
        $structureService = $container->get(StructureService::class);
        $structureGestionnaireService = $container->get(StructureGestionnaireService::class);
        $structureResponsableService = $container->get(StructureResponsableService::class);

        /** @var ResponsabiliteForm $responsabiliteForm */
        $responsabiliteForm = $container->get('FormElementManager')->get(ResponsabiliteForm::class);

        $controller = new StructureResponsabiliteController();
        $controller->setParametreService($parametreService);
        $controller->setRoleService($roleService);
        $controller->setStructureService($structureService);
        $controller->setStructureGestionnaireService($structureGestionnaireService);
        $controller->setStructureResponsableService($structureResponsableService);
        $controller->setResponsabiliteForm($responsabiliteForm);
        return $controller;

    }
}
