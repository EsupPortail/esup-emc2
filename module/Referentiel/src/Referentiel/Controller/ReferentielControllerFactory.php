<?php

namespace Referentiel\Controller;

use Element\Service\Competence\CompetenceService;
use FicheMetier\Service\Activite\ActiviteService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Referentiel\Form\Referentiel\ReferentielForm;
use Referentiel\Service\Referentiel\ReferentielService;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenUtilisateur\Service\User\UserService;

class ReferentielControllerFactory
{

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): ReferentielController
    {
        /**
         * @var ActiviteService $activiteService
         * @var CompetenceService $competenceService
         * @var FicheMetierService $ficheMetierService
         * @var MissionPrincipaleService $missionPrincipaleService
         * @var PrivilegeService $privilegeService
         * @var ReferentielService $referentielService
         * @var UserService $userService
         * @var ReferentielForm $referentielForm
         */
        $activiteService = $container->get(ActiviteService::class);
        $competenceService = $container->get(CompetenceService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $privilegeService = $container->get(PrivilegeService::class);
        $referentielService = $container->get(ReferentielService::class);
        $userService = $container->get(UserService::class);
        $referentielForm = $container->get('FormElementManager')->get(ReferentielForm::class);

        $controller = new ReferentielController();
        $controller->setActiviteService($activiteService);
        $controller->setCompetenceService($competenceService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setMissionPrincipaleService($missionPrincipaleService);
        $controller->setPrivilegeService($privilegeService);
        $controller->setReferentielService($referentielService);
        $controller->setUserService($userService);
        $controller->setReferentielForm($referentielForm);
        return $controller;
    }
}
