<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Provider\Etat\DemandeExterneEtats;
use Formation\Provider\Etat\SessionEtats;
use Formation\Provider\Role\FormationRoles;
use Formation\Provider\Template\TextTemplates;
use Formation\Provider\Validation\MesFormationsValidations;
use Formation\Service\DemandeExterne\DemandeExterneServiceAwareTrait;
use Formation\Service\Session\SessionServiceAwareTrait;
use Formation\Service\StagiaireExterne\StagiaireExterneServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class IndexController extends AbstractActionController
{

    use AgentServiceAwareTrait;
    use DemandeExterneServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use SessionServiceAwareTrait;
    use RenduServiceAwareTrait;
    use StagiaireExterneServiceAwareTrait;
    use UserServiceAwareTrait;


    public function indexAction(): ViewModel|Response
    {
        /** @var User $connectedUser */
        $connectedUser = $this->getUserService()->getConnectedUser();
        $connectedRole = $this->getUserService()->getConnectedRole();

        if ($connectedRole) {
          switch ($connectedRole->getRoleId()) {
              case FormationRoles::GESTIONNAIRE_FORMATION :
                /** @see IndexController::indexGestionnaireAction() */
                return $this->redirect()->toRoute('index-gestionnaire', [], [], true);
              case FormationRoles::RESPONSABLE_FORMATION :
                  /** @see IndexController::indexResponsableAction() */
                  return $this->redirect()->toRoute('index-responsable', [], [], true);
            }
        }

        $agent = $this->getAgentService()->getAgentByUser($connectedUser);
        if ($agent !== null && $agent->getUtilisateur() === null) {
            $previous = $agent->getUtilisateur();
            $agent->setUtilisateur($connectedUser);
            $this->getAgentService()->update($agent);

            if ($connectedUser !== $previous) {
                $this->redirect()->toRoute('home');
            }
        }
        $stagiaire = $this->getStagiaireExterneService()->getStagiaireExterneByUser($connectedUser);
        if ($stagiaire !== null && $stagiaire->getUtilisateur() === null) {
            $previous = $stagiaire->getUtilisateur();
            $stagiaire->setUtilisateur($connectedUser);
            $this->getStagiaireExterneService()->update($stagiaire);

            if ($connectedUser !== $previous) {
                $this->redirect()->toRoute('home');
            }
        }

        if ($agent !== null && $agent->getValidationActiveByTypeCode(MesFormationsValidations::CHARTE_SIGNEE) === null) {
            return $this->redirect()->toRoute('formation/charte', [], [], true);
        }

        $rendu = $this->getRenduService()->generateRenduByTemplateCode(TextTemplates::MES_FORMATIONS_ACCUEIL, [], false);

        return new ViewModel([
            'agent' => $agent,
            'user' => $connectedUser,
            'role' => $this->getUserService()->getConnectedRole(),
            'rendu' => $rendu,
        ]);
    }

    public function indexGestionnaireAction(): ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

        $etatsTypesSession = $this->getEtatTypeService()->getEtatsTypesByCategorieCode(SessionEtats::TYPE);
        $etatsTypesDemande = $this->getEtatTypeService()->getEtatsTypesByCategorieCode(DemandeExterneEtats::TYPE);
        $dictionnaireSession = $this->getSessionService()->getSessionsByGestionnaires($user);
        $sessionsSansGestionnaire = $this->getSessionService()->getSessionsSansGestionnaires();

        $dictionnaireDemande = $this->getDemandeExterneService()->getDemandesExternesByGestionnaires($user);
        $demandesSansGestionnaire = $this->getDemandeExterneService()->getDemandesExternesSansGestionnaires();

        return new ViewModel([
            'user' => $user,
            'role' => $role,
            'etatsTypesSession' => $etatsTypesSession,
            'etatsTypesDemande' => $etatsTypesDemande,

            'dictionnaireSession' => $dictionnaireSession,
            'sessionsSansGestionnaire' => $sessionsSansGestionnaire,

            'dictionnaireDemande' => $dictionnaireDemande,
            'demandesSansGestionnaire' => $demandesSansGestionnaire,
        ]);
    }

    public function indexResponsableAction(): ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();

        $sessions = $this->getSessionService()->getSessionsInPlansActifs();
        $sessionTypes = $this->getEtatTypeService()->getEtatsTypesByCategorieCode(SessionEtats::TYPE);
        $dictionnaireSessions = $this->getSessionService()->sortByGestionnaireAndEtat($sessions);

        $demandes = $this->getDemandeExterneService()->getDemandesExternesByAnnee();
        $demandeTypes = $this->getEtatTypeService()->getEtatsTypesByCategorieCode(DemandeExterneEtats::TYPE);
        $dictionnairesDemandes = $this->getDemandeExterneService()->sortByGestionnaireAndEtat($demandes);

        $gestionnaires = $this->getUserService()->getRoleService()->getUtilisateursByRoleId(FormationRoles::GESTIONNAIRE_FORMATION);
        usort($gestionnaires, function (User $a, User $b) { return $a->getDisplayName() <=> $b->getDisplayName();});

        return new ViewModel([
            'user' => $user,
            'role' => $role,

            'gestionnaires' => $gestionnaires,
            'dictionnaireSessions' => $dictionnaireSessions,
            'sessionTypes' => $sessionTypes,
            'dictionnaireDemandes' => $dictionnairesDemandes,
            'demandeTypes' => $demandeTypes,
        ]);
    }

    /** @noinspection PhpUnused */
    public function aproposAction(): ViewModel
    {
        return new ViewModel([]);
    }

    /** @noinspection PhpUnused */
    public function contactAction(): ViewModel
    {
        return new ViewModel([]);
    }

}