<?php

namespace FicheMetier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleForm;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeForm;
use Carriere\Form\SelectionnerFamillesProfessionnelles\SelectionnerFamillesProfessionnellesForm;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleService;
use Carriere\Service\Niveau\NiveauService;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeService;
use Element\Form\SelectionApplication\SelectionApplicationForm;
use Element\Form\SelectionCompetence\SelectionCompetenceForm;
use Element\Service\ApplicationElement\ApplicationElementService;
use Element\Service\CompetenceElement\CompetenceElementService;
use FicheMetier\Form\MissionPrincipale\MissionPrincipaleForm;
use FicheMetier\Service\CodeFonction\CodeFonctionService;
use FicheMetier\Service\FicheMetier\FicheMetierService;
use FicheMetier\Service\MissionElement\MissionElementService;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleService;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use Referentiel\Service\Referentiel\ReferentielService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenPrivilege\Service\Privilege\PrivilegeService;
use UnicaenRenderer\Service\Rendu\RenduService;
use UnicaenUtilisateur\Service\User\UserService;

class MissionPrincipaleControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return MissionPrincipaleController
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(ContainerInterface $container): MissionPrincipaleController
    {
        /**
         * @var ApplicationElementService $applicationElementService
         * @var CodeFonctionService $codeFonctionService
         * @var CompetenceElementService $competenceElementService
         * @var FamilleProfessionnelleService $familleProfessionnelleService
         * @var FicheMetierService $ficheMetierService
         * @var MissionPrincipaleService $missionPrincipaleService
         * @var MissionElementService $missionElementService
         * @var NiveauService $niveauService
         * @var NiveauEnveloppeService $niveauEnveloppeService
         * @var ParametreService $parametreService
         * @var PrivilegeService $privilegeService
         * @var ReferentielService $referentielService
         * @var RenduService $renduService
         * @var UserService $userService
         */
        $applicationElementService = $container->get(ApplicationElementService::class);
        $codeFonctionService = $container->get(CodeFonctionService::class);
        $competenceElementService = $container->get(CompetenceElementService::class);
        $familleProfessionnelleService = $container->get(FamilleProfessionnelleService::class);
        $ficheMetierService = $container->get(FicheMetierService::class);
        $missionPrincipaleService = $container->get(MissionPrincipaleService::class);
        $missionElementService = $container->get(MissionElementService::class);
        $privilegeService = $container->get(PrivilegeService::class);
        $niveauService = $container->get(NiveauService::class);
        $niveauEnveloppeService = $container->get(NiveauEnveloppeService::class);
        $parametreService = $container->get(ParametreService::class);
        $referentielService = $container->get(ReferentielService::class);
        $renduService = $container->get(RenduService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var MissionPrincipaleForm $missionPrincipaleForm
         * @var ModifierLibelleForm $modifierLibelleForm
         * @var NiveauEnveloppeForm $niveauEnveloppeForm
         * @var SelectionApplicationForm $selectionApplicationForm
         * @var SelectionCompetenceForm $selectionCompetencesForm
         * @var SelectionnerFamillesProfessionnellesForm $selectionnerFamillesProfessionnellesForm
         */
        $missionPrincipaleForm = $container->get('FormElementManager')->get(MissionPrincipaleForm::class);
        $modifierLibelleForm = $container->get('FormElementManager')->get(ModifierLibelleForm::class);
        $niveauEnveloppeForm = $container->get('FormElementManager')->get(NiveauEnveloppeForm::class);
        $selectionApplicationForm = $container->get('FormElementManager')->get(SelectionApplicationForm::class);
        $selectionCompetencesForm = $container->get('FormElementManager')->get(SelectionCompetenceForm::class);
        $selectionnerFamillesProfessionnellesForm = $container->get('FormElementManager')->get(SelectionnerFamillesProfessionnellesForm::class);

        $controller = new MissionPrincipaleController();
        $controller->setApplicationElementService($applicationElementService);
        $controller->setCodeFonctionService($codeFonctionService);
        $controller->setCompetenceElementService($competenceElementService);
        $controller->setFamilleProfessionnelleService($familleProfessionnelleService);
        $controller->setFicheMetierService($ficheMetierService);
        $controller->setMissionPrincipaleService($missionPrincipaleService);
        $controller->setMissionElementService($missionElementService);
        $controller->setNiveauService($niveauService);
        $controller->setNiveauEnveloppeService($niveauEnveloppeService);
        $controller->setParametreService($parametreService);
        $controller->setPrivilegeService($privilegeService);
        $controller->setReferentielService($referentielService);
        $controller->setRenduService($renduService);
        $controller->setUserService($userService);

        $controller->setMissionPrincipaleForm($missionPrincipaleForm);
        $controller->setModifierLibelleForm($modifierLibelleForm);
        $controller->setNiveauEnveloppeForm($niveauEnveloppeForm);
        $controller->setSelectionApplicationForm($selectionApplicationForm);
        $controller->setSelectionCompetenceForm($selectionCompetencesForm);
        $controller->setSelectionnerFamillesProfessionnellesForm($selectionnerFamillesProfessionnellesForm);
        return $controller;
    }
}