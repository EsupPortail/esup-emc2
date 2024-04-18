<?php

namespace Formation\Controller;

use Application\Service\Agent\AgentServiceAwareTrait;
use Formation\Provider\Etat\SessionEtats;
use Formation\Provider\Role\FormationRoles;
use Formation\Provider\Template\TextTemplates;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
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
    use EtatTypeServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use RenduServiceAwareTrait;
    use StagiaireExterneServiceAwareTrait;
    use UserServiceAwareTrait;


    public function indexAction(): ViewModel|Response
    {
        /** @var User $connectedUser */
        $connectedUser = $this->getUserService()->getConnectedUser();
        $connectedRole = $this->getUserService()->getConnectedRole();

        if ($connectedRole->getRoleId() === FormationRoles::GESTIONNAIRE_FORMATION) {
            /** @see IndexController::indexGestionnaireAction() */
            return $this->redirect()->toRoute('index-gestionnaire', [], [], true);
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

        $etatsTypes = $this->getEtatTypeService()->getEtatsTypesByCategorieCode(SessionEtats::TYPE);
        $dictionnaire = $this->getFormationInstanceService()->getSessionsByGestionnaires($user);
        $sansGestionnaire = $this->getFormationInstanceService()->getSessionsSansGestionnaires();

        return new ViewModel([
            'user' => $user,
            'role' => $role,
            'etatsTypes' => $etatsTypes,
            'dictionnaire' => $dictionnaire,
            'sansGestionnaire' => $sansGestionnaire,
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