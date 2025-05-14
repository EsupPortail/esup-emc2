<?php

namespace Application\Controller;

use Agent\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Provider\Role\RoleProvider as AppRoleProvider;
use Application\Provider\Template\TexteTemplate;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\Macro\MacroServiceAwareTrait;
use Application\Service\Url\UrlServiceAwareTrait;
use EntretienProfessionnel\Controller\ObservateurController;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Provider\Role\RoleProvider as EntretienProfessionnelRoleProvider;
use EntretienProfessionnel\Provider\Template\TexteTemplates;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Structure\Controller\ObservateurController as StructureObservateurController;
use Structure\Controller\StructureController;
use Structure\Entity\Db\StructureAgentForce;
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
    use AgentAffectationServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use AgentMissionSpecifiqueServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use MacroServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RenduServiceAwareTrait;
    use RoleServiceAwareTrait;
    use StructureServiceAwareTrait;
    use UserServiceAwareTrait;
    use UserContextServiceAwareTrait;
    use UrlServiceAwareTrait;

    use FichePosteServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;


    public function indexAction(): ViewModel|Response
    {

        $campagnes = $this->getCampagneService()->getCampagnesActives();
        if (count($campagnes) === 1) $campagne = current($campagnes); else $campagne = $this->getCampagneService()->getLastCampagne();

        $vars = ['UrlService' => $this->getUrlService(), 'MacroService' => $this->getMacroService(), 'campagne' => $campagne];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(TexteTemplate::EMC2_ACCUEIL, $vars, false);
        $texte = $rendu->getCorps();

        /** @var User $connectedUser */
        $connectedUser = $this->getUserService()->getConnectedUser();
        if ($connectedUser === null)
            return new ViewModel([
                'user' => null,
                'texte' => $texte,
            ]);

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
                case RoleProvider::OBSERVATEUR :
                    /** @see StructureObservateurController::indexObservateurAction() */
                    return $this->redirect()->toRoute('structure/observateur/index-observateur', [], [], true);
                case EntretienProfessionnelRoleProvider::OBSERVATEUR :
                    /** @see ObservateurController::indexObservateurAction() */
                    return $this->redirect()->toRoute('entretien-professionnel/observateur/index-observateur', [], [], true);
            }
        }


        return new ViewModel([
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

        $agentsRaw = array_map(function (AgentSuperieur $a) {
            return $a->getAgent();
        }, $this->getAgentSuperieurService()->getAgentsSuperieursBySuperieur($agent));
        $agents = []; foreach ($agentsRaw as $agent_) $agents[$agent_->getId()] = $agent_;

        /** Campagne d'entretien professionnel ************************************************************************/
        $last = $this->getCampagneService()->getLastCampagne();
        $campagnes = $this->getCampagneService()->getCampagnesActives();
        if ($last !== null) $campagnes[] = $last;
        usort($campagnes, function (Campagne $a, Campagne $b) {
            return $a->getDateDebut() <=> $b->getDateDebut();
        });

        /** Récuperation des eps **************************************************************************************/
        $entretiens = [];
        $agentsByCampagne = [];

        $templates = [];

        foreach ($campagnes as $campagne) {
            $agentsS = $this->getAgentSuperieurService()->getAgentsWithSuperieur($agent, $campagne->getDateDebut(), $campagne->getDateFin());
            $agentsSCampagnes = [];
            foreach ($agents as $agent) {
                if ($this->getAgentAffectationService()->hasAffectation($agent, $campagne->getDateDebut(), $campagne->getDateFin())) $agentsSCampagnes[$agent->getId()] = $agent;
            }
            foreach ($agentsS as $agent) {
                if ($this->getAgentAffectationService()->hasAffectation($agent, $campagne->getDateDebut(), $campagne->getDateFin())) $agentsSCampagnes[$agent->getId()] = $agent;
            }
            $entretiens[$campagne->getId()] = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agentsSCampagnes, false, false);
            [$obligatoires, $facultatifs, $raison] = $this->getCampagneService()->trierAgents($campagne, $agentsSCampagnes);
            $agentsByCampagne[$campagne->getId()] = [$obligatoires, $facultatifs, $raison];

            $vars = ['campagne' => $campagne, 'UrlService' => $this->getUrlService()];
            $templates[$campagne->getId()][TexteTemplates::EP_EXPLICATION_SANS_OBLIGATION] = $this->getRenduService()->generateRenduByTemplateCode(TexteTemplates::EP_EXPLICATION_SANS_OBLIGATION, $vars, false);
        }

        /** Récupération des fiches de postes *************************************************************************/
        $fichesDePoste = [];
        foreach ($agents as $agent_) {
            if ($agent_ instanceof StructureAgentForce) $agent_ = $agent_->getAgent();
            $fiches = $this->getFichePosteService()->getFichesPostesByAgent($agent_);
            $fichesDePoste[$agent_->getId()] = $fiches;
        }
        $fichesDePostePdf = $this->getAgentService()->getFichesPostesPdfByAgents($agents);

        $missionsSpecifiques = $this->getAgentMissionSpecifiqueService()->getAgentMissionsSpecifiquesByAgents($agents);

        return new ViewModel([
            'agents' => $agents,
            'connectedAgent' => $this->getAgentService()->getAgentByUser($user),

            'missionsSpecifiques' => $missionsSpecifiques,

            'campagnes' => $campagnes,
            'entretiens' => $entretiens,
            'agentsByCampagne' => $agentsByCampagne,

            'fichesDePoste' => $fichesDePoste,
            'fichesDePostePdf' => $fichesDePostePdf,

            'templates' => $templates,
        ]);
    }

    public function indexAutoriteAction(): ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByUser($user);

        $agentsRaw = array_map(function (AgentAutorite $a) {
            return $a->getAgent();
        }, $this->getAgentAutoriteService()->getAgentsAutoritesByAutorite($agent));
        $agents = []; foreach ($agentsRaw as $agent_) $agents[$agent_->getId()] = $agent_;

        /** Campagne d'entretien professionnel ************************************************************************/
        $last = $this->getCampagneService()->getLastCampagne();
        $campagnes = $this->getCampagneService()->getCampagnesActives();
        if ($last !== null) $campagnes[] = $last;
        usort($campagnes, function (Campagne $a, Campagne $b) {
            return $a->getDateDebut() <=> $b->getDateDebut();
        });

        /** Récuperation des eps **************************************************************************************/
        $entretiens = [];
        $agentsByCampagne = [];

        $templates = [];

        foreach ($campagnes as $campagne) {
            $agentsS = $this->getAgentAutoriteService()->getAgentsWithAutorite($agent, $campagne->getDateDebut(), $campagne->getDateFin());
            $agentsSCampagnes = [];
            foreach ($agents as $agent) {
                if ($this->getAgentAffectationService()->hasAffectation($agent, $campagne->getDateDebut(), $campagne->getDateFin())) $agentsSCampagnes[$agent->getId()] = $agent;
            }
            foreach ($agentsS as $agent) {
                if ($this->getAgentAffectationService()->hasAffectation($agent, $campagne->getDateDebut(), $campagne->getDateFin())) $agentsSCampagnes[$agent->getId()] = $agent;
            }
            $entretiens[$campagne->getId()] = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agentsSCampagnes, false, false);
            [$obligatoires, $facultatifs, $raison] = $this->getCampagneService()->trierAgents($campagne, $agentsSCampagnes);
            $agentsByCampagne[$campagne->getId()] = [$obligatoires, $facultatifs, $raison];

            $vars = ['campagne' => $campagne, 'UrlService' => $this->getUrlService()];
            $templates[$campagne->getId()][TexteTemplates::EP_EXPLICATION_SANS_OBLIGATION] = $this->getRenduService()->generateRenduByTemplateCode(TexteTemplates::EP_EXPLICATION_SANS_OBLIGATION, $vars, false);
        }

        /** Récupération des fiches de postes *************************************************************************/
        $fichesDePoste = [];
        foreach ($agents as $agent_) {
            if ($agent_ instanceof StructureAgentForce) $agent_ = $agent_->getAgent();
            $fiches = $this->getFichePosteService()->getFichesPostesByAgent($agent_);
            $fichesDePoste[$agent_->getId()] = $fiches;
        }
        $fichesDePostePdf = $this->getAgentService()->getFichesPostesPdfByAgents($agents);

        $missionsSpecifiques = $this->getAgentMissionSpecifiqueService()->getAgentMissionsSpecifiquesByAgents($agents);

        return new ViewModel([
            'agents' => $agents,
            'connectedAgent' => $this->getAgentService()->getAgentByUser($user),
            'campagnes' => $campagnes,
            'entretiens' => $entretiens,
            'agentsByCampagne' => $agentsByCampagne,

            'missionsSpecifiques' => $missionsSpecifiques,

            'fichesDePoste' => $fichesDePoste,
            'fichesDePostePdf' => $fichesDePostePdf,

            'templates' => $templates,
        ]);
    }

    public function infosAction(): ViewModel
    {
        return new ViewModel();
    }

    public function checkConnectionAction(): JsonModel
    {
        $user = $this->getUserService()->getConnectedUser();
        return new JsonModel(["connection" => $user !== null]);
    }

    public function aproposAction(): ViewModel
    {
        $vars = [
            'MacroService' => $this->getMacroService(),
            'UrlService' => $this->getUrlService(),
        ];
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(TexteTemplate::APROPOS, $vars, false);

        $vm = new ViewModel([
            'rendu' => $rendu,
        ]);
        $vm->setTemplate('default/default-renderer');
        return $vm;
    }
}
