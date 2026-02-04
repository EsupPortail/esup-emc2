<?php

namespace EntretienProfessionnel\Controller;

use Agent\Entity\Db\AgentAffectation;
use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Form\SelectionAgent\SelectionAgentFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Service\Macro\MacroServiceAwareTrait;
use DateTime;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Entity\Db\EntretienProfessionnel;
use EntretienProfessionnel\Form\Campagne\CampagneFormAwareTrait;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Provider\Parametre\EntretienProfessionnelParametres;
use EntretienProfessionnel\Provider\Template\TexteTemplates;
use EntretienProfessionnel\Provider\Validation\EntretienProfessionnelValidations;
use EntretienProfessionnel\Service\Campagne\CampagneService;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementAutoriteServiceAwareTrait;
use EntretienProfessionnel\Service\Evenement\RappelCampagneAvancementSuperieurServiceAwareTrait;
use EntretienProfessionnel\Service\Notification\NotificationServiceAwareTrait;
use EntretienProfessionnel\Service\Url\UrlServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\Mvc\Plugin\FlashMessenger\FlashMessenger;
use Laminas\View\Model\ViewModel;
use RuntimeException;
use Structure\Entity\Db\StructureAgentForce;
use Structure\Service\Structure\StructureServiceAwareTrait;
use Structure\Service\StructureAgentForce\StructureAgentForceServiceAwareTrait;
use UnicaenApp\View\Model\CsvModel;
use UnicaenIndicateur\Service\HasIndicateurs\HasIndicateursServiceAwareTrait;
use UnicaenParametre\Exception\ParametreNotFoundException;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

/** @method FlashMessenger flashMessenger() */
class CampagneController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use HasIndicateursServiceAwareTrait;
    use MacroServiceAwareTrait;
    use NotificationServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use RappelCampagneAvancementAutoriteServiceAwareTrait;
    use RappelCampagneAvancementSuperieurServiceAwareTrait;
    use RenduServiceAwareTrait;
    use StructureServiceAwareTrait;
    use StructureAgentForceServiceAwareTrait;
    use UrlServiceAwareTrait;
    use UserServiceAwareTrait;

    use CampagneFormAwareTrait;
    use SelectionAgentFormAwareTrait;

    public function indexAction(): ViewModel
    {
        $campagnes = $this->getCampagneService()->getCampagnes(true);

        return new ViewModel([
            'campagnes' => $campagnes,
        ]);
    }

    public function afficherAction(): ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $agents = $this->getCampagneService()->getAgentsEligibles($campagne);

        return new ViewModel([
            'campagne' => $campagne,
            'agents' => [],//$this->getCampagneService()->getAgentsEligibles($campagne),
            'nbAgents' => count($agents),
            'entretiens' => $this->getEntretienProfessionnelService()->getEntretiensProfessionnelsByCampagne($campagne, true),
        ]);
    }

    public function ajouterAction(): ViewModel
    {
        $campagne = new Campagne();
        $campagne->setAnnee(CampagneService::getAnneeScolaire());
        $campagne->setDateEnPoste(DateTime::createFromFormat('Y-m-d', (new DateTime('now'))->format('Y') . "-01-01"));

        $form = $this->getCampagneForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/ajouter', [], [], true));
        $form->bind($campagne);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCampagneService()->create($campagne);
                $this->flashMessenger()->addSuccessMessage("La campagne d'entretien professionnel [" . $campagne->getAnnee() . "] vient d'être créée.");

                $this->getRappelCampagneAvancementAutoriteService()->creer($campagne);
                $this->getRappelCampagneAvancementSuperieurService()->creer($campagne);

                try {
                    $notificationsActivees = $this->getParametreService()->getValeurForParametre(EntretienProfessionnelParametres::TYPE, EntretienProfessionnelParametres::CAMPAGNE_NOTIFIER_OUVERTURE);
                } catch (ParametreNotFoundException) {
                    $notificationsActivees = true;
                }
                if ($notificationsActivees) {
                    $this->getNotificationService()->triggerCampagneOuverturePersonnels($campagne);
                    $this->getNotificationService()->triggerCampagneOuvertureDirections($campagne);
                }

                $listing = [
                    [
                        'code' => 'CAMP_' . $campagne->getId(),
                        'libelle' => "Indicateurs liés à la campagne " . $campagne->getAnnee(),
                        'indicateurs' => [
                            ['code' => 'EP', 'libelle' => "Liste des entretiens professionnels", "requete" => "select e.* from entretienprofessionnel e join public.entretienprofessionnel_campagne ec on e.campagne_id = ec.id where ec.id = :campagne"],
                            ['code' => 'AUTRE', 'libelle' => "Autre", "requete" => "select * from unicaen_utilisateur_role"],
                        ],
                    ],
                ];

                $log = $this->getHasIndicateursService()->ajouterIndicateurs($campagne, $listing, ":campagne");
                if ($log !== '') $this->flashMessenger()->addWarningMessage($log);

            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Ajout d'une campagne d'entretien professionnel",
            'form' => $form,
        ]);
        return $vm;
    }

    /**
     * Fonction permettant de re-expédier les notifications d'ouverture de campagne.
     * Attention : il n'y a pas de bouton pour invoquer cette fonction, il faut utiliser la route directement.
     */
    public function notifierOuvertureAction(): ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $this->getNotificationService()->triggerCampagneOuverturePersonnels($campagne);
        $this->getNotificationService()->triggerCampagneOuvertureDirections($campagne);

        return new ViewModel(['title' => "Notification de l'ouverture de la campagne"]);
    }

    public function modifierAction(): ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);

        $form = $this->getCampagneForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/modifier', ['campagne' => $campagne->getId()], [], true));
        $form->bind($campagne);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getCampagneService()->update($campagne);
                $this->flashMessenger()->addSuccessMessage("La campagne d'entretien professionnel [" . $campagne->getAnnee() . "] vient d'être modifiée.");
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Modification d'une campagne d'entretien professionnel",
            'form' => $form,
        ]);
        return $vm;
    }

    public function historiserAction(): Response
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $this->getCampagneService()->historise($campagne);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('entretien-professionnel/campagne',[],[], true);
    }

    public function restaurerAction(): Response
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $this->getCampagneService()->restore($campagne);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('entretien-professionnel/campagne', [], [], true);
    }

    public function detruireAction(): ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {

                //nettoyage des indicateurs associés
                $this->getHasIndicateursService()->retirerIndicateurs($campagne);

                //nettoyage des événements todo ???


                $this->getCampagneService()->delete($campagne);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($campagne !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la campagne " . $campagne->getAnnee(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('entretien-professionnel/campagne/detruire', ["campagne" => $campagne->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** Action de la page d'affichage d'une campagne ******************************************************************/

    public function demanderValidationSuperieurAction(): ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $enAttente = $this->getCampagneService()->getEntretiensEnAttenteResponsable($campagne);

        $entretiens = [];
        $listes = [];
        foreach ($enAttente as $entretienListe) {
            /** @var EntretienProfessionnel $entretien */
            foreach ($entretienListe as $entretien) {
                if ($entretien->estNonHistorise()) {
                    $superieur = $entretien->getResponsable();
                    $listes[$superieur->getId()] = $superieur;
                    $entretiens[$superieur->getId()][] = $entretien;
                }
            }
        }

        $count = 0;
        $texte = "Liste des notifications :";
        $texte .= "<ul>";
        foreach ($listes as $superieur) {
            $mail = $this->getNotificationService()->triggerRappelValidationSuperieur($superieur, $campagne, $entretiens[$superieur->getId()]);
            $texte .= "<li> Notification faite vers " . $superieur->getDenomination() . " (" . $superieur->getEmail() . ") mail#" . $mail->getId() . "</li>";
            $count++;
        }
        $texte .= "</ul>";
        $texte .= $count . " notification·s <br/>";

        $vm = new ViewModel([
            'title' => "Rapport de notification",
            'reponse' => $texte,
        ]);
        $vm->setTemplate('default/reponse');
        return $vm;
    }

    public function demanderValidationAutoriteAction(): ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $enAttente = $this->getCampagneService()->getEntretiensEnAttenteAutorite($campagne);

        $entretiens = [];
        $problemes = [];
        $listes = [];
        foreach ($enAttente as $entretienListe) {
            /** @var EntretienProfessionnel $entretien */
            foreach ($entretienListe as $entretien) {
                if ($entretien->estNonHistorise()) {
                    $agent = $entretien->getAgent();
                    $autorites_tmp = array_map(function (AgentAutorite $aa) {
                        return $aa->getAutorite();
                    }, $agent->getAutorites());
                    $autorites = [];
                    foreach ($autorites_tmp as $autorite) {
                        if ($autorite !== $entretien->getResponsable()) $autorites[] = $autorite;
                    }

                    if (!empty($autorites)) {
                        foreach ($autorites as $autorite) {
                            $listes[$autorite->getId()] = $autorite;
                            $entretiens[$autorite->getId()][] = $entretien;
                        }
                    } else {
                        $problemes[] = $entretien;
                    }
                }
            }
        }

        $count = 0;
        $texte = "Liste des notifications :";
        $texte .= "<ul>";
        foreach ($listes as $autorite) {
            $mail = $this->getNotificationService()->triggerRappelValidationAutorite($autorite, $campagne, $entretiens[$autorite->getId()]);
            $texte .= "<li> Notification faite vers " . $autorite->getDenomination() . " (" . $autorite->getEmail() . ") mail#" . $mail->getId() . "</li>";
            $count++;
        }
        $texte .= "</ul>";
        $texte .= $count . " notification·s <br/>";

        $texte .= "Liste des problèmes : ";
        $texte .= "<ul>";
        foreach ($problemes as $probleme) {
            $texte .= "<li> Entretien de " . $probleme->getAgent()->getDenomination() . "</li>";
        }
        $texte .= "</ul>";

        $vm = new ViewModel([
            'title' => "Rapport de notification",
            'reponse' => $texte,
        ]);
        $vm->setTemplate('default/reponse');
        return $vm;
    }

    public function demanderValidationAgentAction(): ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $enAttente = $this->getCampagneService()->getEntretiensEnAttenteAgent($campagne);

        $count = 0;
        $texte = "Liste des notifications :";
        $texte .= "<ul>";
        foreach ($enAttente as $entretienListe) {
            /** @var EntretienProfessionnel $entretien */
            foreach ($entretienListe as $entretien) {
                if ($entretien->estNonHistorise()) {
                    $agent = $entretien->getAgent();
                    $mail = $this->getNotificationService()->triggerRappelValidationAgent($entretien);
                    $texte .= "<li> Notification faite vers " . $agent->getDenomination() . " (" . $agent->getEmail() . ") mail#" . $mail->getId() . "</li>";
                    $count++;
                }
            }
        }
        $texte .= "</ul>";
        $texte .= $count . " notification·s";

        $vm = new ViewModel([
            'title' => "Rapport de notification",
            'reponse' => $texte,
        ]);
        $vm->setTemplate('default/reponse');
        return $vm;
    }

    public function extraireAction(): CsvModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);

        /** @var Agent[] $agents */
        $agents = $this->getCampagneService()->getAgentsEligibles($campagne);
        $entretiens = $campagne->getEntretiensProfessionnels();
        foreach ($entretiens as $entretien) $agents[$entretien->getAgent()->getId()] = $entretien->getAgent();

        $headers = ['Identifiant', 'Prénom', 'Nom', 'Affectations', 'Supérieur·es', 'Autorités', 'Responsable d\'entretien', 'Validation du responsable', 'Validation de l\'autorité', 'Validation de l\'agent'];
        $resume = [];
        foreach ($agents as $agent) {
            $resume[$agent->getId()] = [
                'Identifiant' => '',
                'Prénom' => $agent->getPrenom(),
                'Nom' => $agent->getNomUsuel() ?? $agent->getNomFamille(),
                'Affectations' => implode("\n", array_map(function (AgentAffectation $a) {
                    return $a->getStructure()->getLibelleLong();
                }, $agent->getAffectationsActifs($campagne->getDateDebut()))),
                'Supérieur·es' => implode("\n", array_map(function (AgentSuperieur $a) {
                    return $a->getSuperieur()->getDenomination();
                }, $agent->getSuperieurs())),
                'Autorités' => implode("\n", array_map(function (AgentAutorite $a) {
                    return $a->getAutorite()->getDenomination();
                }, $agent->getAutorites())),
                'Responsable d\'entretien' => '',
                'Validation du responsable' => '',
                'Validation de l\'autorité' => '',
                'Validation de l\'agent' => '',
            ];
        }

        foreach ($entretiens as $entretien) {
            if ($entretien->estNonHistorise()) {
                $resume[$entretien->getAgent()->getId()]['Identifiant'] = $entretien->getId();
                $resume[$entretien->getAgent()->getId()]['Responsable d\'entretien'] = $entretien->getResponsable()->getDenomination();
                $validationResponsable = $entretien->getValidationActiveByTypeCode(EntretienProfessionnelValidations::VALIDATION_RESPONSABLE);
                if ($validationResponsable) $resume[$entretien->getAgent()->getId()]['Validation du responsable'] = $validationResponsable->getHistoCreation()->format('d/m/Y à H:i');
                $validationAutorite = $entretien->getValidationActiveByTypeCode(EntretienProfessionnelValidations::VALIDATION_DRH);
                if ($validationAutorite) $resume[$entretien->getAgent()->getId()]['Validation de l\'autorité'] = $validationAutorite->getHistoCreation()->format('d/m/Y à H:i');
                $validationAgent = $entretien->getValidationActiveByTypeCode(EntretienProfessionnelValidations::VALIDATION_AGENT);
                if ($validationAgent) $resume[$entretien->getAgent()->getId()]['Validation de l\'agent'] = $validationAgent->getHistoCreation()->format('d/m/Y à H:i');
            }
        }

        $date = (new DateTime())->format('Ymd-His');
        $filename = $date . "_export_campagne_" . str_replace('/', '-', $campagne->getAnnee()) . ".csv";
        $CSV = new CsvModel();
        $CSV->setDelimiter(';');
        $CSV->setEnclosure('"');
        $CSV->setHeader($headers);
        $CSV->setData($resume);
        $CSV->setFilename($filename);
        return $CSV;
    }

    /** Page associée à la campagne dans l'écran des structures *******************************************************/

    /**
     * Action affichant une campagne d'entretien professionnel pour une structure
     * Attention : les agents doivent être complétement hydratés sinon les calculs d'affectations, de grades et d'obligation seront erronés
     */
    public function structureAction(): ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        if ($campagne === null) $campagne = $this->getCampagneService()->getBestCampagne();
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $selecteur = $this->getStructureService()->getStructuresByCurrentRole();

        if ($structure === null) {
            throw new RuntimeException("Aucune structure de trouvée.");
        }
        $structures = $this->getStructureService()->getStructuresFilles($structure, true);

        // récupération des agents selon les critères de la structure
        $agents = $this->getAgentService()->getAgentsByStructures($structures, $campagne->getDateDebut(), $campagne->getDateFin());
        $agentsForces = array_map(function (StructureAgentForce $agentForce) {
            return $agentForce->getAgent();
        }, $this->getStructureAgentForceService()->getStructureAgentsForcesByStructures($structures));
        foreach ($agentsForces as $agentForce) {
            if (!in_array($agentForce, $agents)) {
                $agents[] = $agentForce;
            }
        }


        [$obligatoires, $facultatifs, $raison] = $this->getCampagneService()->trierAgents($campagne, $agents, $structures);

        $entretiens = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agents, false, false);
        $finalises = [];
        $encours = [];
        foreach ($entretiens as $entretien) {
            if ($entretien->isEtatActif(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT)) {
                $finalises[] = $entretien;
            } else {
                $encours[] = $entretien;
            }
        }

//        $last = $this->getCampagneService()->getLastCampagne();
//        $campagnes = $this->getCampagneService()->getCampagnesActives();
//        $campagnesFutures = $this->getCampagneService()->getCampagnesFutures();
//        if ($last !== null) $campagnes[] = $last;
//        usort($campagnes, function (Campagne $a, Campagne $b) {
//            return $a->getDateDebut() <=> $b->getDateDebut();
//        });

            $campagnes = $this->getCampagneService()->getCampagnes();

        /** GENERATION DES CONTENUS TEMPLATISÉS ***********************************************************************/
        $vars = ['UrlService' => $this->getUrlService(), 'campagne' => $campagne, 'structure' => $structure];
        $templates = [];
        $templates[TexteTemplates::EP_EXPLICATION_SANS_OBLIGATION] = $this->getRenduService()->generateRenduByTemplateCode(TexteTemplates::EP_EXPLICATION_SANS_OBLIGATION, $vars, false);

        return new ViewModel([
            'campagne' => $campagne,
            'campagnes' => $campagnes,
//            'campagnesFutures' => $campagnesFutures,
            'structure' => $structure,
            'selecteur' => $selecteur,
            'structures' => $structures,
            'agents' => $agents,

            'entretiens' => $entretiens,
            'encours' => $encours,
            'finalises' => $finalises,

            'obligatoires' => $obligatoires,
            'facultatifs' => $facultatifs,
            'raison' => $raison,

            'templates' => $templates,
        ]);
    }

    public function structureProgressionAction(): ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $structures = $this->getStructureService()->getStructuresFilles($structure, true);

        // récupération des agents selon les critères de la structure
        $agents = $this->getAgentService()->getAgentsByStructures($structures, $campagne->getDateDebut());
        $agentsForces = array_map(function (StructureAgentForce $agentForce) {
            return $agentForce->getAgent();
        }, $this->getStructureAgentForceService()->getStructureAgentsForcesByStructures($structures));
        foreach ($agentsForces as $agentForce) {
            if (!in_array($agentForce, $agents)) {
                $agents[] = $agentForce;
            }
        }


        [$obligatoires, $facultatifs, $raison] = $this->getCampagneService()->trierAgents($campagne, $agents);

        $entretiens = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agents, false, false);
        $finalises = [];
        $encours = [];
        foreach ($entretiens as $entretien) {
            if ($entretien->isEtatActif(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT)) {
                $finalises[] = $entretien;
            } else {
                $encours[] = $entretien;
            }
        }

        $last = $this->getCampagneService()->getLastCampagne();
        $campagnes = $this->getCampagneService()->getCampagnesActives();
        if ($last !== null) $campagnes[] = $last;
        usort($campagnes, function (Campagne $a, Campagne $b) {
            return $a->getDateDebut() <=> $b->getDateDebut();
        });

        return new ViewModel([
            'campagne' => $campagne,
            'campagnes' => $campagnes,
            'structure' => $structure,
            'structures' => $structures,
            'agents' => $agents,

            'entretiens' => $entretiens,
            'encours' => $encours,
            'finalises' => $finalises,

            'obligatoires' => $obligatoires,
            'facultatifs' => $facultatifs,
            'raison' => $raison,
        ]);
    }

    /** TESTS ET DEBUGS ***********************************************************************************************/

    public function notifierAvancementAutoriteAction(): ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $autorite = $this->getAgentService()->getRequestedAgent($this);

        $request = $this->getRequest();
        if ($autorite !== null || $request->isPost()) {

            if ($request->isPost()) {
                $data = $request->getPost();
                $autoriteId = $data['agent-sas']['id'];
                $autorite = $this->getAgentService()->getAgent($autoriteId);
            }
            $listing = $this->getAgentAutoriteService()->getAgentsWithAutorite($autorite, $campagne->getDateDebut(), $campagne->getDateFin());

            if (!empty($listing)) {
                $mail = $this->getNotificationService()->triggerRappelCampagneAutorite($campagne, $autorite);

                $vm = new ViewModel([
                    'title' => "Notification de " . $autorite->getDenomination() . " de l'avancement de la campagne " . $campagne->getAnnee(),
                    'reponse' => ($mail) ? "<h1 class='page-header'>Contenu de la notification générée</h1><h2>Sujet</h2> <div>" . $mail->getSujet() . "</div>" . "<h2>Corps</h2> <div>" . $mail->getCorps() . "</div>" : "Aucun mail",

                ]);
            } else {
                $vm = new ViewModel([
                    'title' => "Notification de " . $autorite->getDenomination() . " de l'avancement de la campagne " . $campagne->getAnnee(),
                    'error' => "L'agent n'est l'autorité d'aucune personne pour la campagne",

                ]);
            }
            $vm->setTemplate('default/reponse');
            return $vm;
        }

        $form = $this->getSelectionAgentForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/notifier-avancement-autorite', ['campagne' => $campagne->getId()], [], true));

        $vm = new ViewModel([
            'title' => "Sélectionner l'agent à notifier (en tant qu'autorité)",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function notifierAvancementSuperieurAction(): ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $superieur = $this->getAgentService()->getRequestedAgent($this);

        $request = $this->getRequest();
        if ($superieur !== null || $request->isPost()) {

            if ($request->isPost()) {
                $data = $request->getPost();
                $superieurId = $data['agent-sas']['id'];
                $superieur = $this->getAgentService()->getAgent($superieurId);
            }
            $listing = $this->getAgentSuperieurService()->getAgentsWithSuperieur($superieur, $campagne->getDateDebut(), $campagne->getDateFin());

            if (!empty($listing)) {
                $mail = $this->getNotificationService()->triggerRappelCampagneSuperieur($campagne, $superieur);

                $vm = new ViewModel([
                    'title' => "Notification de " . $superieur->getDenomination() . " de l'avancement de la campagne " . $campagne->getAnnee(),
                    'reponse' => $mail ? "<h1 class='page-header'>Contenu de la notification générée</h1><h2>Sujet</h2> <div>" . $mail->getSujet() . "</div>" . "<h2>Corps</h2> <div>" . $mail->getCorps() . "</div>" : "Aucun mail",

                ]);

            } else {
                $vm = new ViewModel([
                    'title' => "Notification de " . $superieur->getDenomination() . " de l'avancement de la campagne " . $campagne->getAnnee(),
                    'error' => "L'agent n'est le ou la supérieur·e d'aucune personne pour la campagne",

                ]);
            }
            $vm->setTemplate('default/reponse');
            return $vm;
        }

        $form = $this->getSelectionAgentForm();
        $form->setAttribute('action', $this->url()->fromRoute('entretien-professionnel/campagne/notifier-avancement-superieur', ['campagne' => $campagne->getId()], [], true));

        $vm = new ViewModel([
            'title' => "Sélectionner l'agent à notifier (en tant que supérieur·e)",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function testerEligibiliteAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);

        [$obligatoires, $facultatifs, $raison] = $this->getCampagneService()->trierAgents($campagne, [$agent]);

        return new ViewModel([
            'agent' => $agent,
            'campagne' => $campagne,

            'obligatoires' => $obligatoires,
            'facultatifs' => $facultatifs,
            'raisons' => $raison,
        ]);
    }


    /** VUES STRATEGIQUES *********************************************************************************************/

    public function indicateursAction(): ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $indicateurs = $campagne->getIndicateurs();

        return new ViewModel([
            'campagne' => $campagne,
            'indicateurs' => $indicateurs,
        ]);
    }

    public function progressionParStructuresAction(): ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        $structures = $this->getStructureService()->getStructuresNiv2($campagne->getDateDebut());

        return new ViewModel([
            'campagne' => $campagne,
            'structures' => $structures,
        ]);
    }
}
