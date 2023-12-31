<?php


namespace Application\Controller;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use UnicaenEtat\Service\EtatCategorie\EtatCategorieService;
use UnicaenEtat\Service\EtatType\EtatTypeService;
use UnicaenEvenement\Service\Type\TypeService;
use UnicaenParametre\Service\Categorie\CategorieService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenRenderer\Service\Template\TemplateService;
use UnicaenUtilisateur\Service\Role\RoleService;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;

class VerificationControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return VerificationController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): VerificationController
    {
        /**
         * @var EtatCategorieService $etatCategorieService
         * @var EtatTypeService $etatTypeService
         * @var PrivilegeService $privilegeService
         * @var TemplateService $templateService
         * @var TypeService $evenementTypeService
         * @var RoleService $roleService
         * @var ValidationTypeService $validationTypeService
         *
         * @var ParametreService $parametreService
         * @var CategorieService $categorieService
         */
        $etatCategorieService = $container->get(EtatCategorieService::class);
        $etatTypeService = $container->get(EtatTypeService::class);
        $privilegeService = $container->get(PrivilegeService::class);
        $templateService = $container->get(TemplateService::class);
        $evenementTypeService = $container->get(TypeService::class);
        $roleService = $container->get(RoleService::class);
        $validationTypeService = $container->get(ValidationTypeService::class);
        $parametreService = $container->get(ParametreService::class);
        $categorieService = $container->get(CategorieService::class);

        $controller = new VerificationController();
        $controller->setEtatCategorieService($etatCategorieService);
        $controller->setEtatTypeService($etatTypeService);
        $controller->setPrivilegeService($privilegeService);
        $controller->setTemplateService($templateService);
        $controller->setRoleService($roleService);
        $controller->setTypeService($evenementTypeService);
        $controller->setValidationTypeService($validationTypeService);

        $controller->setParametreService($parametreService);
        $controller->setCategorieService($categorieService);

        return $controller;
    }
}