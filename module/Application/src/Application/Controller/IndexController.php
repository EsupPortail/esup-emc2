<?php

namespace Application\Controller;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Application\Provider\Template\TexteTemplate;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Formation\Service\DemandeExterne\DemandeExterneServiceAwareTrait;
use Formation\Service\Inscription\InscriptionServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Structure\Controller\StructureController;
use Structure\Entity\Db\StructureAgentForce;
use Structure\Provider\Parametre\StructureParametres;
use Structure\Provider\Role\RoleProvider;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenAuthentification\Service\Traits\UserContextServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenUtilisateur\Entity\Db\Role;
use UnicaenUtilisateur\Entity\Db\User;
use UnicaenUtilisateur\Service\Role\RoleServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class IndexController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;
    use RoleServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;
    use UserContextServiceAwareTrait;

    use FichePosteServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use InscriptionServiceAwareTrait;
    use DemandeExterneServiceAwareTrait;

    public function indexAction(): ViewModel|Response
    {
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(TexteTemplate::EMC2_ACCUEIL, [], false);
        $texte = $rendu->getCorps();

        /** @var User $connectedUser */
        $connectedUser = $this->getUserService()->getConnectedUser();
        if ($connectedUser === null) return new ViewModel(['user' => null, 'texte' => $texte]);

        $agent = $this->getAgentService()->getAgentByUser($connectedUser);
        if ($agent !== null && $agent->getUtilisateur() === null) {
            $agent->setUtilisateur($connectedUser);
            $this->getAgentService()->update($agent);
//            return $this->redirect()->toRoute('agent/afficher', ['agent' => $agent->getId()], [], true);
        }

        /** @var Role $connectedRole */
        $connectedRole = $this->getUserService()->getConnectedRole();

        if ($connectedRole) {
            switch ($connectedRole->getRoleId()) {
                case AppRoleProvider::AGENT :
                    $agent = $this->getAgentService()->getAgentByUser($connectedUser);
                    /** @see AgentController::afficherAction() */
                    return $this->redirect()->toRoute('agent/afficher', ['agent' => $agent->getId()], [], true);
                case RoleProvider::RESPONSABLE :
                    $structures = $this->getStructureService()->getStructuresByResponsable($connectedUser);
                    /** @see StructureController::descriptionAction() */
                    if (!empty($structures)) return $this->redirect()->toRoute('structure/description', ['structure' => $structures[0]->getId()], [], true);
                    break;
                case Agent::ROLE_SUPERIEURE :
                    /** @see IndexController::indexSuperieurAction() */
                    return $this->redirect()->toRoute('index-superieur', [], [], true);
                case Agent::ROLE_AUTORITE :
                    /** @see IndexController::indexAutoriteAction() */
                    return $this->redirect()->toRoute('index-autorite', [], [], true);
            }
        }


        return new ViewModel([
            'user' => $connectedUser,
            'role' => $connectedRole,
            'texte' => $texte,
        ]);
    }

    public function indexRessourcesAction(): ViewModel
    {
        return new ViewModel();
    }

    public function indexGestionAction(): ViewModel
    {
        return new ViewModel();
    }

    public function indexAdministrationAction(): ViewModel
    {
        return new ViewModel();
    }

    public function indexSuperieurAction(): ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);

        $agents = array_map(function (AgentSuperieur $a) {
            return $a->getAgent();
        }, $this->getAgentSuperieurService()->getAgentsSuperieursBySuperieur($agent));
        $agents = $this->getAgentService()->filtrerWithStatutTemoin($agents, $this->getParametreService()->getParametreByCode(StructureParametres::TYPE, StructureParametres::AGENT_TEMOIN_STATUT));
        $agents = $this->getAgentService()->filtrerWithAffectationTemoin($agents, $this->getParametreService()->getParametreByCode(StructureParametres::TYPE, StructureParametres::AGENT_TEMOIN_AFFECTATION));
        usort($agents, function (Agent $a, Agent $b) {
            return $a->getNomUsuel() . " " . $a->getPrenom() > $b->getNomUsuel() . " " . $b->getPrenom();
        });

        /** Campagne d'entretien professionnel ************************************************************************/
        $last = $this->getCampagneService()->getLastCampagne();
        $campagnes = $this->getCampagneService()->getCampagnesActives();
        if ($last !== null) $campagnes[] = $last;
        usort($campagnes, function (Campagne $a, Campagne $b) {
            return $a->getDateDebut() > $b->getDateDebut();
        });

        /** Récuperation des eps **************************************************************************************/
        $entretiens = [];
        foreach ($campagnes as $campagne) {
            $entretiens[$campagne->getId()] = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agents);
        }

        /** Récupération des fiches de postes *************************************************************************/
        $fichesDePoste = [];
        foreach ($agents as $agent_) {
            if ($agent_ instanceof StructureAgentForce) $agent_ = $agent_->getAgent();
            $fiches = $this->getFichePosteService()->getFichesPostesByAgent($agent_);
            $fichesDePoste[$agent_->getId()] = $fiches;
        }
        $fichesDePostePdf = $this->getAgentService()->getFichesPostesPdfByAgents($agents);

        return new ViewModel([
            'agents' => $agents,
            'connectedAgent' => $agent,

            'campagnes' => $campagnes,
            'entretiens' => $entretiens,

            'fichesDePoste' => $fichesDePoste,
            'fichesDePostePdf' => $fichesDePostePdf,
        ]);
    }

    public function indexAutoriteAction(): ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);

        $agents = array_map(function (AgentAutorite $a) {
            return $a->getAgent();
        }, $this->getAgentAutoriteService()->getAgentsAutoritesByAutorite($agent));
        $agents = $this->getAgentService()->filtrerWithStatutTemoin($agents, $this->getParametreService()->getParametreByCode(StructureParametres::TYPE, StructureParametres::AGENT_TEMOIN_STATUT));
        $agents = $this->getAgentService()->filtrerWithAffectationTemoin($agents, $this->getParametreService()->getParametreByCode(StructureParametres::TYPE, StructureParametres::AGENT_TEMOIN_AFFECTATION));

        usort($agents, function (Agent $a, Agent $b) {
            return $a->getNomUsuel() . " " . $a->getPrenom() > $b->getNomUsuel() . " " . $b->getPrenom();
        });

        /** Campagne d'entretien professionnel ************************************************************************/
        $last = $this->getCampagneService()->getLastCampagne();
        $campagnes = $this->getCampagneService()->getCampagnesActives();
        if ($last !== null) $campagnes[] = $last;
        usort($campagnes, function (Campagne $a, Campagne $b) {
            return $a->getDateDebut() > $b->getDateDebut();
        });

        /** Récuperation des eps **************************************************************************************/
        $entretiens = [];
        foreach ($campagnes as $campagne) {
            $entretiens[$campagne->getId()] = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agents);
        }

        /** Récupération des fiches de postes *************************************************************************/
        $fichesDePoste = [];
        foreach ($agents as $agent_) {
            if ($agent_ instanceof StructureAgentForce) $agent_ = $agent_->getAgent();
            $fiches = $this->getFichePosteService()->getFichesPostesByAgent($agent_);
            $fichesDePoste[$agent_->getId()] = $fiches;
        }
        $fichesDePostePdf = $this->getAgentService()->getFichesPostesPdfByAgents($agents);

        return new ViewModel([
            'agents' => $agents,
            'connectedAgent' => $agent,
            'campagnes' => $campagnes,
            'entretiens' => $entretiens,

            'fichesDePoste' => $fichesDePoste,
            'fichesDePostePdf' => $fichesDePostePdf,
        ]);
    }

    public function infosAction(): ViewModel
    {
        return new ViewModel();
    }
}
