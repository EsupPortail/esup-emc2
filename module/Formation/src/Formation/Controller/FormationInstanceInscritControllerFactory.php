<?php

namespace Formation\Controller;

use Application\Form\SelectionAgent\SelectionAgentForm;
use Application\Service\Agent\AgentService;
use Formation\Service\FormationInstance\FormationInstanceService;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritService;
use Interop\Container\ContainerInterface;
use UnicaenEtat\Service\Etat\EtatService;
use UnicaenMail\Service\Mail\MailService;
use UnicaenParametre\Service\Parametre\ParametreService;
use UnicaenRenderer\Service\Contenu\ContenuService;
use UnicaenUtilisateur\Service\User\UserService;

class FormationInstanceInscritControllerFactory
{

    /**
     * @param ContainerInterface $container
     * @return FormationInstanceInscritController
     */
    public function __invoke(ContainerInterface $container)
    {
        /**
         * @var AgentService $agentService
         * @var ContenuService $contenuService
         * @var EtatService $etatService
         * @var FormationInstanceService $formationInstanceService
         * @var FormationInstanceInscritService $formationInstanceInscritService
         * @var MailService $mailService
         * @var ParametreService $parametreService
         * @var UserService $userService
         */
        $agentService = $container->get(AgentService::class);
        $contenuService = $container->get(ContenuService::class);
        $etatService = $container->get(EtatService::class);
        $formationInstanceService = $container->get(FormationInstanceService::class);
        $formationInstanceInscritService = $container->get(FormationInstanceInscritService::class);
        $mailService = $container->get(MailService::class);
        $parametreService = $container->get(ParametreService::class);
        $userService = $container->get(UserService::class);

        /**
         * @var SelectionAgentForm $selectionAgentForm
         */
        $selectionAgentForm = $container->get('FormElementManager')->get(SelectionAgentForm::class);

        $controller = new FormationInstanceInscritController();
        $controller->setAgentService($agentService);
        $controller->setContenuService($contenuService);
        $controller->setEtatService($etatService);
        $controller->setFormationInstanceService($formationInstanceService);
        $controller->setFormationInstanceInscritService($formationInstanceInscritService);
        $controller->setMailService($mailService);
        $controller->setParametreService($parametreService);
        $controller->setUserService($userService);
        $controller->setSelectionAgentForm($selectionAgentForm);
        return $controller;
    }
}