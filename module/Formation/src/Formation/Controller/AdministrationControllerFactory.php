<?php

namespace Formation\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenIndicateur\Service\Indicateur\IndicateurService;
use UnicaenIndicateur\Service\TableauDeBord\TableauDeBordService;
use UnicaenParametre\Service\Categorie\CategorieService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenRenderer\Service\Template\TemplateService;
use UnicaenUtilisateur\Service\Role\RoleService;

class AdministrationControllerFactory {

    /**
     * @param ContainerInterface $container
     * @return AdministrationController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container) : AdministrationController
    {
        /**
         * @var CategorieService $categorieService
         * @var EtatTypeService $etatTypeService
         * @var IndicateurService $indicateurService
         * @var ParametreService $parametreService
         * @var PrivilegeService $privilegeService
         * @var RoleService $roleService
         * @var TableauDeBordService $tableauService
         * @var TemplateService $templateService
         */
        $categorieService = $container->get(CategorieService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $indicateurService = $container->get(IndicateurService::class);
        $parametreService = $container->get(ParametreService::class);
        $privilegeService = $container->get(PrivilegeService::class);
        $roleService = $container->get(RoleService::class);
        $tableauService = $container->get(TableauDeBordService::class);
        $templateService = $container->get(TemplateService::class);

        $controller = new AdministrationController();
        $controller->setCategorieService($categorieService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setIndicateurService($indicateurService);
        $controller->setParametreService($parametreService);
        $controller->setPrivilegeService($privilegeService);
        $controller->setRoleService($roleService);
        $controller->setTableauDeBordService($tableauService);
        $controller->setTemplateService($templateService);
        return $controller;
    }
}