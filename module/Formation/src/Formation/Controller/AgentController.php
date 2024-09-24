<?php

namespace Formation\Controller;

use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Application\Service\AgentStatut\AgentStatutServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use DateTime;
use Formation\Entity\Db\DemandeExterne;
use Formation\Entity\Db\Inscription;
use Formation\Provider\Etat\DemandeExterneEtats;
use Formation\Provider\Etat\InscriptionEtats;
use Formation\Provider\Etat\SessionEtats;
use Formation\Provider\Template\TextTemplates;
use Formation\Provider\Validation\MesFormationsValidations;
use Formation\Service\DemandeExterne\DemandeExterneServiceAwareTrait;
use Formation\Service\Inscription\InscriptionServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use UnicaenApp\View\Model\CsvModel;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;
use UnicaenValidation\Service\ValidationInstance\ValidationInstanceServiceAwareTrait;

class AgentController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentAffectationServiceAwareTrait;
    use AgentGradeServiceAwareTrait;
    use AgentStatutServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use DemandeExterneServiceAwareTrait;
    use InscriptionServiceAwareTrait;
    use RenduServiceAwareTrait;
    use UserServiceAwareTrait;
    use ValidationInstanceServiceAwareTrait;


    public function indexAction(): ViewModel
    {
        $params = $this->params()->fromQuery();
        $agents = [];
        if ($params !== null and !empty($params)) {
            $agents = $this->getAgentService()->getAgentsWithFiltre($params);
        }

        return new ViewModel([
            'agents' => $agents,
            'params' => $params,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $agentAffectations = $this->getAgentAffectationService()->getAgentAffectationsByAgent($agent);
        $agentGrades = $this->getAgentGradeService()->getAgentGradesByAgent($agent);
        $agentStatuts = $this->getAgentStatutService()->getAgentStatutsByAgent($agent);
        $superieures = array_map(function (AgentSuperieur $a) {
            return $a->getSuperieur();
        }, $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($agent));
        $autorites = array_map(function (AgentAutorite $a) {
            return $a->getAutorite();
        }, $this->getAgentAutoriteService()->getAgentsAutoritesByAgent($agent));

        $formations = $this->getInscriptionService()->getInscriptionsByAgent($agent);
        $inscriptions = $this->getInscriptionService()->getInscriptionsByAgent($agent);

        $demandes = $this->getDemandeExterneService()->getDemandesExternesByAgent($agent);
        $demandes = array_filter($demandes, function (DemandeExterne $d) {
            return (
                $d->estNonHistorise() &&
                !$d->isEtatActif(DemandeExterneEtats::ETAT_REJETEE) &&
                !$d->isEtatActif(DemandeExterneEtats::ETAT_TERMINEE)
            );
        });

        return new ViewModel([
            'agent' => $agent,
            'agentAffectations' => $agentAffectations,
            'agentGrades' => $agentGrades,
            'agentStatuts' => $agentStatuts,

            'superieures' => $superieures,
            'autorites' => $autorites,

            'inscriptions' => $inscriptions,
            'stages' => $demandes,
            'formations' => $formations,
        ]);
    }

    public function mesAgentsAction(): ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();
        $agent = $this->getAgentService()->getAgentByUser($user);

        $agents = [];
        if ($role->getRoleId() === Agent::ROLE_SUPERIEURE) $agents = array_map(function (AgentSuperieur $a) {
            return $a->getAgent();
        }, $this->getAgentSuperieurService()->getAgentsSuperieursBySuperieur($agent));
        if ($role->getRoleId() === Agent::ROLE_AUTORITE) $agents = array_map(function (AgentAutorite $a) {
            return $a->getAgent();
        }, $this->getAgentAutoriteService()->getAgentsAutoritesByAutorite($agent));

        $inscriptions = $this->getInscriptionService()->getInscriptionsByAgents($agents);
        $inscriptionsEnAttente = array_filter($inscriptions, function (Inscription $inscription) {
            return $inscription->isEtatActif(InscriptionEtats::ETAT_DEMANDE and $inscription->getSession()->isEtatActif(SessionEtats::ETAT_INSCRIPTION_OUVERTE));
        });
        $demandes = $this->getDemandeExterneService()->getDemandesExternesByAgents($agents);
        $demandesEnAttente = array_filter($demandes, function (DemandeExterne $demande) {
            return $demande->isEtatActif(DemandeExterneEtats::ETAT_VALIDATION_AGENT);
        });


        return new ViewModel([
            'user' => $user,
            'role' => $role,
            'agents' => $agents,

            'inscriptions' => $inscriptions,
            'inscriptionsEnAttente' => $inscriptionsEnAttente,
            'demandes' => $demandes,
            'demandesEnAttente' => $demandesEnAttente,
        ]);
    }

    public function listerMesAgentsAction(): ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();
        $agent = $this->getAgentService()->getAgentByUser($user);

        $agents = [];
        if ($role->getRoleId() === Agent::ROLE_SUPERIEURE)
            $agents = array_map(
                function (AgentSuperieur $a) {
                    return $a->getAgent();
                },
                $this->getAgentSuperieurService()->getAgentsSuperieursBySuperieur($agent));
        if ($role->getRoleId() === Agent::ROLE_AUTORITE)
            $agents = array_map(function (AgentAutorite $a) {
                return $a->getAgent();
            },
                $this->getAgentAutoriteService()->getAgentsAutoritesByAutorite($agent));

        usort($agents, function (Agent $a, Agent $b) {
            $aaa = $a->getNomUsuel() . " " . $a->getPrenom() . " " . $a->getId();
            $bbb = $b->getNomUsuel() . " " . $b->getPrenom() . " " . $b->getId();
            return $aaa <=> $bbb;
        });
        return new ViewModel([
            'title' => "Liste des agents dont je suis responsable",
            'user' => $user,
            'role' => $role,
            'agents' => $agents,
        ]);
    }

    public function extraireInscriptionsAction(): CsvModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();
        $agent = $this->getAgentService()->getAgentByUser($user);

        $agents = [];
        if ($role->getRoleId() === Agent::ROLE_SUPERIEURE)
            $agents = array_map(
                function (AgentSuperieur $a) {
                    return $a->getAgent();
                },
                $this->getAgentSuperieurService()->getAgentsSuperieursBySuperieur($agent));
        if ($role->getRoleId() === Agent::ROLE_AUTORITE)
            $agents = array_map(function (AgentAutorite $a) {
                return $a->getAgent();
            },
                $this->getAgentAutoriteService()->getAgentsAutoritesByAutorite($agent));

        $inscriptions = $this->getInscriptionService()->getInscriptionsByAgents($agents);
        $headers = ["annee", "prenom", "nom", "action de formation", "session", "etat de la session", "etat de l'inscription", "liste", "duree", "presence"];
        $records = [];
        foreach ($inscriptions as $inscription) {
            $records[] = [
                "annee" => $inscription->getSession()->getAnnee(),
                "prenom" => $inscription->getAgent()->getPrenom(),
                "nom" => $inscription->getAgent()->getNomUsuel(),
                "action de formation" => $inscription->getSession()->getFormation()->getLibelle(),
                "session" => $inscription->getSession()->getPeriode(),
                "etat de la session" => $inscription->getSession()->getEtatActif()->getType()->getLibelle(),
                "etat de l'inscription" => $inscription->getEtatActif()->getType()->getLibelle(),
                "liste" => ($inscription->getListe()??"Non classée"),
                "duree" => $inscription->getSession()->getDuree(),
                "presence" => $inscription->getDureePresence(),
            ];
        }

        $date = (new DateTime())->format('Ymd-His');
        $filename="export_mesformations_inscriptions_".$date.".csv";
        $CSV = new CsvModel();
        $CSV->setDelimiter(';');
        $CSV->setEnclosure('"');
        $CSV->setHeader($headers);
        $CSV->setData($records);
        $CSV->setFilename($filename);
        return $CSV;
    }

    public function extraireDemandesAction(): CsvModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $role = $this->getUserService()->getConnectedRole();
        $agent = $this->getAgentService()->getAgentByUser($user);

        $agents = [];
        if ($role->getRoleId() === Agent::ROLE_SUPERIEURE)
            $agents = array_map(
                function (AgentSuperieur $a) {
                    return $a->getAgent();
                },
                $this->getAgentSuperieurService()->getAgentsSuperieursBySuperieur($agent));
        if ($role->getRoleId() === Agent::ROLE_AUTORITE)
            $agents = array_map(function (AgentAutorite $a) {
                return $a->getAgent();
            },
                $this->getAgentAutoriteService()->getAgentsAutoritesByAutorite($agent));

        $demandes = $this->getDemandeExterneService()->getDemandesExternesByAgents($agents);
        $headers = ["annee", "prenom", "nom", "action de formation", "date", "organisme", "lieu", "montant", "etat de la demande"];
        $records = [];
        foreach ($demandes as $demande) {
            $records[] = [
                "annee" => $demande->getAnnee(),
                "prenom" => $demande->getAgent()->getPrenom(),
                "nom" => $demande->getAgent()->getNomUsuel(),
                "action de formation" => $demande->getLibelle(),
                "date" => $demande->getPeriode(),
                "organisme" => $demande->getOrganisme(),
                "lieu" => $demande->getLieu(),
                "montant" => $demande->getMontant(),
                "etat de la demande" => $demande->getEtatActif()->getTypeLibelle(),
            ];
        }

        $date = (new DateTime())->format('Ymd-His');
        $filename="export_mesformations_inscriptions_".$date.".csv";
        $CSV = new CsvModel();
        $CSV->setDelimiter(';');
        $CSV->setEnclosure('"');
        $CSV->setHeader($headers);
        $CSV->setData($records);
        $CSV->setFilename($filename);
        return $CSV;
    }

    public function afficherCharteAction(): ViewModel
    {
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByConnectedUser($user);
        $rendu = $this->getRenduService()->generateRenduByTemplateCode(TextTemplates::FORMATION_CHARTE, ['agent' => $agent], false);

        return new ViewModel([
            'title' => $rendu->getSujet(),
            'charte' => $rendu->getCorps(),
        ]);
    }

    public function validerCharteAction(): Response
    {
        $user = $this->getUserService()->getConnectedUser();
        $agent = $this->getAgentService()->getAgentByConnectedUser($user);

        $instance = $this->getValidationInstanceService()->createWithCode(MesFormationsValidations::CHARTE_SIGNEE);
        $agent->addValidation($instance);
        $this->getAgentService()->update($agent);

        return $this->redirect()->toRoute('index-mes-formations', [], [], true);
    }

    public function historiqueAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $inscriptions = $this->getInscriptionService()->getInscriptionsValideesByAgents([$agent], null);

        return new ViewModel([
            'title' => "Historique des formations de " . $agent->getDenomination(true),
            'agent' => $agent,
            'inscriptions' => $inscriptions,
        ]);
    }

}