<?php

namespace Agent\Controller;

use Agent\Entity\Db\Agent;
use Agent\Entity\Db\AgentAffectation;
use Agent\Entity\Db\AgentAutorite;
use Agent\Entity\Db\AgentSuperieur;
use Agent\Form\AgentHierarchieCalcul\AgentHierarchieCalculFormAwareTrait;
use Agent\Form\AgentHierarchieImportation\AgentHierarchieImportationFormAwareTrait;
use Agent\Form\Chaine\ChaineFormAwareTrait;
use Agent\Provider\Role\RoleProvider as AgentRoleProvider;
use Agent\Service\Agent\AgentServiceAwareTrait;
use Agent\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Agent\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Agent\Service\AgentGrade\AgentGradeServiceAwareTrait;
use Agent\Service\AgentRef\AgentRefServiceAwareTrait;
use Agent\Service\AgentStatut\AgentStatutServiceAwareTrait;
use Agent\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Application\Provider\Parametre\GlobalParametres;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\Url\UrlServiceAwareTrait;
use DateTime;
use EntretienProfessionnel\Provider\Etat\EntretienProfessionnelEtats;
use EntretienProfessionnel\Provider\Template\TexteTemplates;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use FichePoste\Provider\Parametre\FichePosteParametres;
use FichePoste\Provider\Template\TextTemplates;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use RuntimeException;
use Structure\Entity\Db\StructureAgentForce;
use Structure\Entity\Db\StructureResponsable;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\View\Model\CsvModel;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenRenderer\Service\Template\TemplateServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class AgentHierarchieController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentRefServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use StructureServiceAwareTrait;
    use AgentHierarchieCalculFormAwareTrait;
    use AgentHierarchieImportationFormAwareTrait;
    use ChaineFormAwareTrait;

    use AgentAffectationServiceAwareTrait;
    use AgentGradeServiceAwareTrait;
    use AgentMissionSpecifiqueServiceAwareTrait;
    use AgentStatutServiceAwareTrait;
    use CampagneServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use RenduServiceAwareTrait;
    use TemplateServiceAwareTrait;
    use UserServiceAwareTrait;
    use UrlServiceAwareTrait;

    public function indexAction(): ViewModel
    {
        return new ViewModel([]);
    }

    public function autoriteAction(): ViewModel
    {
        $autorites = $this->getAgentAutoriteService()->getAgentsAutoritesCourants();

        $chaines = [];
        foreach ($autorites as $autorite) {
            $agent = $autorite->getAutorite();
            $chaines[$agent->getId()]['agent'] = $agent;
            $chaines[$agent->getId()]['chaines'][] = $autorite;
        }

        return new ViewModel([
            'chaines' => $chaines
        ]);
    }

    public function superieurAction(): ViewModel
    {
        $superieurs = $this->getAgentSuperieurService()->getAgentsSuperieursCourants();

        $chaines = [];
        foreach ($superieurs as $superieur) {
            $agent = $superieur->getSuperieur();
            $chaines[$agent->getId()]['agent'] = $agent;
            $chaines[$agent->getId()]['chaines'][] = $superieur;
        }

        return new ViewModel([
            'chaines' => $chaines
        ]);
    }

    public function afficherChaineAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $type = $this->params()->fromRoute('type');

        $responsabilite = "inconnu";
        $chaines = [];
        if ($type === 'AUTORITE') {
            $chaines = $this->getAgentAutoriteService()->getAgentsAutoritesByAutorite($agent, true);
            $responsabilite = "autorité hiérarchique";
        }
        if ($type === 'SUPERIEUR') {
            $chaines = $this->getAgentSuperieurService()->getAgentsSuperieursBySuperieur($agent, true);
            $responsabilite = "supérieur·e hiérarchique direct·e";
        }

        $now = new DateTime();

        $revoquees = [];
        $historisees = [];
        $valides = [];
        foreach ($chaines as $chaine) {
            if ($chaine->getSourceId() !== 'EMC2' and $chaine->estHistorise()) {
                $revoquees[] = $chaine;
            } else {
                if ($chaine->estHistorise()) {
                    $historisees[] = $chaine;
                } else {
                    $valides[] = $chaine;
                }
            }
        }

        $terminees = [];
        $encours = [];
        $avenir = [];
        foreach ($valides as $valide) {
            if ($chaine->estFini($now)) $terminees[] = $valide;
            if (!$chaine->estCommence()) $avenir[] = $valide;
            if ($chaine->estEnCours()) $encours[] = $valide;
        }

        return new ViewModel([
            'title' => "Affichage des responsabilités pour " . $agent->getDenomination() . " en tant que " . $responsabilite,
            'agent' => $agent,
            'type' => $type,
            'chaines' => $chaines,

            'appName' => $this->getParametreService()->getValeurForParametre(GlobalParametres::TYPE, GlobalParametres::APP_NAME),
            'revoquees' => $revoquees,
            'historisees' => $historisees,
            'terminees' => $terminees,
            'encours' => $encours,
            'avenir' => $avenir,
        ]);
    }


    public function afficherAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $superieurs = $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($agent);
        usort($superieurs, function (AgentSuperieur $a, AgentSuperieur $b) {
            return $a->getSuperieur()->getNomUsuel() . ' ' . $a->getSuperieur()->getPrenom() > $b->getSuperieur()->getNomUsuel() . ' ' . $b->getSuperieur()->getPrenom();
        });
        $autorites = $this->getAgentAutoriteService()->getAgentsAutoritesByAgent($agent, true);
        usort($autorites, function (AgentAutorite $a, AgentAutorite $b) {
            return $a->getAutorite()->getNomUsuel() . ' ' . $a->getAutorite()->getPrenom() > $b->getAutorite()->getNomUsuel() . ' ' . $b->getAutorite()->getPrenom();
        });

        return new ViewModel([
            'title' => "Chaîne hiérarchique de [" . $agent->getDenomination() . "]",
            'agent' => $agent,
            'superieurs' => $superieurs,
            'autorites' => $autorites,
        ]);
    }

    public function importerAction(): ViewModel
    {
        $type = $this->params()->fromRoute('type');

        $form = $this->getAgentHierarchieImportationForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/hierarchie/importer', ['mode' => 'preview', 'path' => null], [], true));


        $request = $this->getRequest();
        if ($request->isPost()) {
            $error = [];
            $data = $request->getPost();
            $file = $request->getFiles();

            $fichier_path = $file['fichier']['tmp_name'];
            $mode = $data['mode'];
            $source = $data['source'];

            //reading
            $array = [];
            $warning = [];
            $chaines = [];
            $agents = [];

            if ($fichier_path === null or $fichier_path === '') {
                $error[] = "Aucun fichier !";
            } else {
                $handle = fopen($fichier_path, "r");

                while ($content = fgetcsv($handle, 0, ";")) {
                    $array[] = $content;
                }


                foreach ($array as $line) {
                    $agent_id = $line[0] ?? null;
                    if ($source === 'EMC2') $agent = $this->getAgentService()->getAgent($agent_id);
                    else $agent = $this->getAgentRefService()->getAgentByRef($source, $agent_id);
                    if ($agent === null) $warning[] = "Aucun·e agent·e de trouvé·e avec l'identifiant [" . $agent_id . "]";
                    $responsable_id = $line[1] ?? null;
                    if ($source === 'EMC2') $responsable = $this->getAgentService()->getAgent($responsable_id);
                    else $responsable = $this->getAgentRefService()->getAgentByRef($source, $responsable_id);
                    if ($responsable === null) $warning[] = "Aucun·e responsable de trouvé·e avec l'identifiant [" . $responsable_id . "]";
                    $date_debut_st = $line[2] ?? null;
                    $dateDebut = ($date_debut_st) ? DateTime::createFromFormat('d/m/Y', $date_debut_st) : null;
                    if ($dateDebut === false) $warning[] = "Impossibilité de calculer la date de début à partir de [" . $date_debut_st . "]";
                    $date_fin_st = $line[3] ?? null;
                    $dateFin = ($date_fin_st) ? DateTime::createFromFormat('d/m/Y', $date_fin_st) : null;
                    if ($date_fin_st !== '' and $dateFin === false) $warning[] = "Impossibilité de calculer la date de fin à partir de [" . $date_fin_st . "]";

                    $chaines[] = [$agent, $responsable, $dateDebut, $dateFin];
                    if ($agent) $agents[$agent->getId()] = $agent;
                }
            }

            if ($mode === 'import' and empty($error)) {
                foreach ($agents as $agent) {
                    switch ($type) {
                        case AgentRoleProvider::ROLE_SUPERIEURE :
                            $this->getAgentSuperieurService()->historiseAll($agent);
                            break;
                        case AgentRoleProvider::ROLE_AUTORITE :
                            $this->getAgentAutoriteService()->historiseAll($agent);
                            break;
                        default:
                            throw new RuntimeException("Type de responsabilité [" . $type . "] inconnu");
                    }
                }
                foreach ($chaines as $chaine) {
                    switch ($type) {
                        case AgentRoleProvider::ROLE_SUPERIEURE :
                            $this->getAgentSuperieurService()->createAgentSuperieurWithArray($chaine);
                            break;
                        case AgentRoleProvider::ROLE_AUTORITE :
                            $this->getAgentAutoriteService()->createAgentAutoriteWithArray($chaine);
                            break;
                        default:
                            throw new RuntimeException("Type de responsabilité [" . $type . "] inconnu");
                    }
                }
            }

            if ($mode !== 'import') {
                $title = "Importation de chaînes hiérarchiques (Prévisualisation)";
            }
            if ($mode === 'import') {
                $title = "Importation de chaînes hiérarchiques (Importation)";
            }
            return new ViewModel([
                'title' => $title,
                'type' => $type,
                'fichier_path' => $fichier_path,
                'form' => $form,
                'mode' => $mode,
                'error' => $error,
                'warning' => $warning,
                'chaines' => $chaines,
            ]);
        }

        $vm = new ViewModel([
            'title' => "Importation de chaînes hiérarchiques",
            'form' => $form,
        ]);
        return $vm;
    }

    public function calculerAction(): ViewModel
    {
        $type = $this->params()->fromRoute('type');


        $form = $this->getAgentHierarchieCalculForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/hierarchie/calculer', ['mode' => 'preview', 'structure' => null], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $error = [];
            $warning = [];
            $data = $request->getPost();

            $mode = $data['mode'];
            $structureId = $data['structure'];

            $structure = $this->getStructureService()->getStructure($structureId);
            $structures = $this->getStructureService()->getStructuresFilles($structure, true);
            $agents = $this->getAgentService()->getAgentsByStructures($structures);

            $superieurs = [];
            if ($type === AgentRoleProvider::ROLE_SUPERIEURE) {
                foreach ($agents as $agent) {
                    $superieurs[$agent->getId()] = [];
                    $affectation = $agent->getAffectationPrincipale();
                    if ($affectation === null) {
                        $warning[] = "L'agent·e [" . $agent->getDenomination() . "] n'a pas d'affectation principale.";
                    } else {
                        $structureAffectation = $affectation->getStructure();
                        $responsables = $structureAffectation?->getResponsables();
                        $responsables = array_filter($responsables, function (StructureResponsable $responsable) use ($agent) {
                            return $responsable->getAgent() !== $agent;
                        });
                        while (empty($responsables) and $structureAffectation and $structureAffectation !== $affectation->getStructure()->getNiv2()) {
                            $structureAffectation = $structureAffectation->getParent();
                            $responsables = $structureAffectation->getResponsables();
                            $responsables = array_filter($responsables, function (StructureResponsable $responsable) use ($agent) {
                                return $responsable->getAgent() !== $agent;
                            });
                        }
                        if (empty($responsables)) {
                            $warning[] = "Calcul impossible pour l'agent·e [" . $agent->getDenomination() . "]";
                        } else {
                            foreach ($responsables as $responsable) {
                                $superieur = new AgentSuperieur();
                                $superieur->setSuperieur($responsable->getAgent());
                                $superieur->setAgent($agent);
                                $superieur->setDateDebut($responsable->getDateDebut());
                                $superieur->setDateFin($responsable->getDateFin());
                                $superieur->setInsertedOn(new DateTime());
                                $superieur->setId($superieur->generateId() . "-computed-" . ((new DateTime())->format('Ymdhis')));
                                $superieurs[$agent->getId()][] = $superieur;
                            }
                        }
                    }
                }
            }

            $autorites = [];
            if ($type === AgentRoleProvider::ROLE_AUTORITE) {

                foreach ($agents as $agent) {
                    $autorites[$agent->getId()] = [];
                    $affectation = $agent->getAffectationPrincipale();

                    if ($affectation === null) {
                        $warning[] = "L'agent·e [" . $agent->getDenomination() . "] n'a pas d'affectation principale.";
                    } else {
                        $structureNiv2 = $affectation->getStructure()->getNiv2();
                        $responsables = ($structureNiv2) ? $structureNiv2->getResponsables() : [];
                        $responsables = array_filter($responsables, function (StructureResponsable $responsable) use ($agent) {
                            return $responsable->getAgent() !== $agent;
                        });

                        if (empty($responsables)) {
                            $warning[] = "Calcul impossible pour l'agent·e [" . $agent->getDenomination() . "]";
                        } else {
                            foreach ($responsables as $responsable) {
                                $autorite = new AgentAutorite();
                                $autorite->setAutorite($responsable->getAgent());
                                $autorite->setAgent($agent);
                                $autorite->setDateDebut($responsable->getDateDebut());
                                $autorite->setDateFin($responsable->getDateFin());
                                $autorite->setInsertedOn(new DateTime());
                                $autorite->setId($autorite->generateId() . "-computed-" . ((new DateTime())->format('Ymdhis')));
                                $autorites[$agent->getId()][] = $autorite;
                            }
                        }
                    }
                }
            }

            if ($mode === 'compute') {
                foreach ($superieurs as $agentId => $superieursListe) {
                    $this->getAgentSuperieurService()->historiseAll($agents[$agentId]);
                    foreach ($superieursListe as $superieur) {
                        $this->getAgentSuperieurService()->create($superieur);
                    }
                }
                foreach ($autorites as $agentId => $autoritesListe) {
                    $this->getAgentAutoriteService()->historiseAll($agents[$agentId]);
                    foreach ($autoritesListe as $autorite) {
                        $this->getAgentAutoriteService()->create($autorite);
                    }
                }
            }


            if ($mode !== 'compute') {
                $title = "Calcul de chaînes hiérarchiques (Prévisualisation)";
            }
            if ($mode === 'compute') {
                $title = "Calcul de chaînes hiérarchiques (Importation)";
            }

            $form->get('structure')->setValue($structure->getId());
            $form->get('mode')->setValue($mode);
            return new ViewModel([
                'title' => $title,
                'type' => $type,
                'form' => $form,
                'structure' => $structure,
                'agents' => $agents,
                'superieurs' => $superieurs,
                'autorites' => $autorites,
                'error' => $error,
                'warning' => $warning,
            ]);
        }

        return new ViewModel(['title' => "Calcul de chaînes hiérarchiques",
            'form' => $form, 'type' => $type, 'superieurs' => [], 'autorites' => [],]);
    }

    public
    function chaineHierarchiqueJsonAction(): JsonModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $superieurs = $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($agent);
        $autorites = $this->getAgentAutoriteService()->getAgentsAutoritesByAgent($agent);


        $data = [];
        //agent
        $data['AgentId'] = $agent->getId();
        $data['AgentLibelle'] = $agent->getDenomination();
        //superieurs
        $position = 1;
        foreach ($superieurs as $superieur) {
            $data['Superieur' . $position . 'Id'] = $superieur->getSuperieur()->getId();
            $data['Superieur' . $position . 'Libelle'] = $superieur->getSuperieur()->getDenomination();
            $position++;
        }
        //autorites
        $position = 1;
        foreach ($autorites as $autorite) {
            $data['Autorite' . $position . 'Id'] = $autorite->getAutorite()->getId();
            $data['Autorite' . $position . 'Libelle'] = $autorite->getAutorite()->getDenomination();
            $position++;
        }

        return new JsonModel($data);
    }

    /** Fonction de recherche *****************************************************************************************/

    public
    function rechercherAgentWithAutoriteAction(): JsonModel
    {
        $superieur = $this->getAgentService()->getAgentByConnectedUser();

        if ($superieur !== null and ($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getAgentAutoriteService()->getAgentsWithAutoriteAndTerm($superieur, $term);
            $result = $this->getAgentService()->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit();
    }

    public
    function rechercherAgentWithSuperieurAction(): JsonModel
    {
        $superieur = $this->getAgentService()->getAgentByConnectedUser();

        if ($superieur !== null and ($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getAgentSuperieurService()->getAgentsWithSuperieurAndTerm($superieur, $term);
            $result = $this->getAgentService()->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit();
    }

    public function ajouterAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $type = $this->params()->fromRoute('type');

        $chaine = match ($type) {
            'superieur' => new AgentSuperieur(),
            'autorite' => new AgentAutorite(),
            default => throw new RuntimeException("AgentHierarchieController::ajouterAction() : Le type [" . $type . "] est inconnu"),
        };
        $chaine->setAgent($agent);

        $form = $this->getChaineForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/hierarchie/ajouter', ['agent' => $agent?->getId(), 'type' => $type], [], true));
        $form->bind($chaine);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                if ($data['historisation'] === '1') {
                    switch ($type) {
                        case 'superieur':
                            $this->getAgentSuperieurService()->historiseAll($chaine->getAgent());
                            break;
                        case 'autorite':
                            $this->getAgentAutoriteService()->historiseAll($chaine->getAgent());
                            break;
                        default :
                            throw new RuntimeException("AgentHierarchieController::ajouterAction() : Le type [" . $type . "] est inconnu");
                    }
                }
                if ($data['cloture'] === '1') switch ($type) {
                    case 'superieur':
                        $this->getAgentSuperieurService()->clotureAll($chaine->getAgent());
                        break;
                    case 'autorite':
                        $this->getAgentAutoriteService()->clotureAll($chaine->getAgent());
                        break;
                }
                $id = $chaine->generateId();
                $chaine->setId($id);
                $chaine->setSourceId("EMC2");
                $chaine->setInsertedOn(new DateTime());
                switch ($type) {
                    case 'superieur':
                        $this->getAgentSuperieurService()->create($chaine);
                        break;
                    case 'autorite':
                        $this->getAgentAutoriteService()->create($chaine);
                        break;
                    default :
                        throw new RuntimeException("AgentHierarchieController::ajouterAction() : Le type [" . $type . "] est inconnu");
                }
                exit();
            }
        }

        $titre = match ($type) {
            'superieur' => "Ajout d'un·e supérieur·e",
            'autorite' => "Ajout d'une autorité",
            default => throw new RuntimeException("AgentHierarchieController::ajouterAction() : Le type [" . $type . "] est inconnu"),
        };

        $vm = new ViewModel([
            'title' => $titre,
            'form' => $form,
            'js' => ($agent) ? "$('#agent-autocomplete').parent().hide();" : "",
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public
    function modifierAction(): ViewModel
    {
        $type = $this->params()->fromRoute('type');
        $chaineId = $this->params()->fromRoute('chaine');

        $chaine = match ($type) {
            'superieur' => $this->getAgentSuperieurService()->getAgentSuperieur($chaineId),
            'autorite' => $this->getAgentAutoriteService()->getAgentAutorite($chaineId),
            default => throw new RuntimeException("AgentHierarchieController::modifierAction() : Le type [" . $type . "] est inconnu"),
        };

        $form = $this->getChaineForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/hierarchie/modifier', ['chaine' => $chaine->getId(), 'type' => $type], [], true));
        $form->bind($chaine);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $id = $chaine->generateId();
                $chaine->setId($id);
                $chaine->setUpdatedOn(new DateTime());
                switch ($type) {
                    case 'superieur':
                        $this->getAgentSuperieurService()->update($chaine);
                        break;
                    case 'autorite':
                        $this->getAgentAutoriteService()->update($chaine);
                        break;
                    default :
                        throw new RuntimeException("AgentHierarchieController::modifierAction() : Le type [" . $type . "] est inconnu");
                }
                exit();
            }
        }

        $titre = match ($type) {
            'superieur' => "Modification d'un·e supérieur·e",
            'autorite' => "Modification d'une autorité",
            default => throw new RuntimeException("AgentHierarchieController::modifierAction() : Le type [" . $type . "] est inconnu"),
        };

        $vm = new ViewModel([
            'title' => $titre,
            'form' => $form,
            'js' => "$('#cloture').parent().hide(); $('#historisation').parent().hide();" . (($chaine->getAgent()) ? "$('#agent-autocomplete').parent().hide();" : ""),
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public
    function historiserAction(): Response
    {
        $type = $this->params()->fromRoute('type');
        $chaineId = $this->params()->fromRoute('chaine');

        switch ($type) {
            case 'superieur' :
                $chaine = $this->getAgentSuperieurService()->getAgentSuperieur($chaineId);
                $this->getAgentSuperieurService()->historise($chaine);
                break;
            case 'autorite' :
                $chaine = $this->getAgentAutoriteService()->getAgentAutorite($chaineId);
                $this->getAgentAutoriteService()->historise($chaine);
                break;
            default :
                throw new RuntimeException("AgentHierarchieController::historiserAction() : Le type [" . $type . "] est inconnu");
        }

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        /** @see \Agent\Controller\AgentController::informationsAction() */
        return $this->redirect()->toRoute('agent/informations', ['agent' => $chaine->getAgent()->getId()], [], true);
    }

    public
    function restaurerAction(): Response
    {
        $type = $this->params()->fromRoute('type');
        $chaineId = $this->params()->fromRoute('chaine');

        switch ($type) {
            case 'superieur' :
                $chaine = $this->getAgentSuperieurService()->getAgentSuperieur($chaineId);
                $this->getAgentSuperieurService()->restore($chaine);
                break;
            case 'autorite' :
                $chaine = $this->getAgentAutoriteService()->getAgentAutorite($chaineId);
                $this->getAgentAutoriteService()->restore($chaine);
                break;
            default :
                throw new RuntimeException("AgentHierarchieController::historiserAction() : Le type [" . $type . "] est inconnu");
        }

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        /** @see \Agent\Controller\AgentController::informationsAction() */
        return $this->redirect()->toRoute('agent/informations', ['agent' => $chaine->getAgent()->getId()], [], true);
    }

    public
    function supprimerAction(): ViewModel
    {
        $type = $this->params()->fromRoute('type');
        $chaineId = $this->params()->fromRoute('chaine');

        $chaine = match ($type) {
            'superieur' => $this->getAgentSuperieurService()->getAgentSuperieur($chaineId),
            'autorite' => $this->getAgentAutoriteService()->getAgentAutorite($chaineId),
            default => throw new RuntimeException("AgentHierarchieController::modifierAction() : Le type [" . $type . "] est inconnu"),
        };


        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                switch ($type) {
                    case 'superieur' :
                        $this->getAgentSuperieurService()->delete($chaine);
                        break;
                    case 'autorite' :
                        $this->getAgentAutoriteService()->delete($chaine);
                        break;
                    default :
                        throw new RuntimeException("AgentHierarchieController::supprimerAction() : Le type [" . $type . "] est inconnu");
                }
            }
            exit();
        }

        $vm = new ViewModel();
        if ($chaine !== null) {
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression d'une la chaîne hiérarchique de  " . $chaine->getAgent()->getDenomination(),
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('agent/hierarchie/supprimer', ["chaine" => $chaine->getId(), 'type' => $type], [], true),
            ]);
        }
        return $vm;
    }

    public function visualiserAction(): ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $type = $this->params()->fromRoute('type');

        $chaines = null;
        switch ($type) {
            case 'superieur' :
                $chaines = $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($agent, true, false);
                break;
            case 'autorite' :
                $chaines = $this->getAgentAutoriteService()->getAgentsAutoritesByAgent($agent, true, false);
                break;
        }

        $title = "Historique des ";
        if ($type === 'autorite') $title .= "autorité·s hiérarchiques";
        if ($type === 'superieur') $title .= "supérieur·es hiérarchiques direct·es";
        $title .= " de " . $agent->getDenomination();

        $vm = new ViewModel([
            'title' => $title,
            'agent' => $agent,
            'type' => $type,
            'chaines' => $chaines,
        ]);
        return $vm;
    }

    public function exporterChainesAction(): CsvModel
    {
        $type = $this->params()->fromRoute('type');
        $chaines =
            match ($type) {
                'superieur' => $this->getAgentSuperieurService()->getAgentsSuperieursCourants(),
                'autorite' => $this->getAgentAutoriteService()->getAgentsAutoritesCourants(),
                default => null
            };
        if ($chaines === null) throw new RuntimeException("Le type [" . $type . "] est inconnu.");

        $header = ["agent", "agent_id", $type, $type . "_id", "structure·s d'affectation", "debut", "fin"];
        $data = [];
        foreach ($chaines as $chaine) {
            $data[] =
                [
                    $chaine->getAgent()->getDenomination(),
                    $chaine->getAgent()->getId(),
                    match ($type) {
                        'superieur' => $chaine->getSuperieur()->getDenomination(),
                        'autorite' => $chaine->getAutorite()->getDenomination(),
                        default => null,
                    },
                    match ($type) {
                        'superieur' => $chaine->getSuperieur()->getId(),
                        'autorite' => $chaine->getAutorite()->getId(),
                        default => null,
                    },
                    implode("|", array_map(function (AgentAffectation $a) {
                        return $a->getStructure()->getLibelleCourt();
                    }, $chaine->getAgent()->getAffectationsActifs())),
                    ($chaine->getDateDebut())?->format('d/m/Y'),
                    ($chaine->getDateFin())?->format('d/m/Y'),
                ];
        }

        $date = (new DateTime())->format('Ymd-His');
        $filename = "chaine_" . $type . "_" . $date . ".csv";
        $CSV = new CsvModel();
        $CSV->setDelimiter(';');
        $CSV->setEnclosure('"');
        $CSV->setHeader($header);
        $CSV->setData($data);
        $CSV->setFilename($filename);
        return $CSV;

    }

    public function mesAgentsAction(): ViewModel
    {
        $agents = $this->listerAgents();

        $affectations = $this->getAgentAffectationService()->getAgentsAffectationsByAgents($agents);
        $grades = $this->getAgentGradeService()->getAgentGradesByAgents($agents);
        $statuts = $this->getAgentStatutService()->getAgentStatutsByAgents($agents);

        $campagne = $this->getCampagneService()->getBestCampagne();

        $campagnes = $this->getCampagneService()->getCampagnesActives();
        $obligations = [];
        foreach ($campagnes as $campagne_) {
            [$obligation] = $this->getCampagneService()->trierAgents($campagne_, $agents);
            $obligations[$campagne_->getId()] = $obligation;
        }


        $vm = new ViewModel([
            'agents' => $agents,

            'campagne' => $campagne,
            'affectations' => $affectations,
            'grades' => $grades,
            'statuts' => $statuts,

            'campagnes' => $campagnes,
            'obligations' => $obligations,
        ]);
        return $vm;
    }

    public function mesMissionsSpecifiquesAction(): ViewModel
    {
        $agents = $this->listerAgents();

        $missions = $this->getAgentMissionSpecifiqueService()->getAgentMissionsSpecifiquesByAgents($agents);
        $campagne = $this->getCampagneService()->getBestCampagne();

        $vm = new ViewModel([
            'agents' => $agents,
            'campagne' => $campagne,

            'missions' => $missions,
        ]);
        return $vm;
    }

    public function mesFichesPostesAction(): ViewModel
    {
        $agents = $this->listerAgents();
        $campagne = $this->getCampagneService()->getBestCampagne();

        $fichesDePoste = [];
        foreach ($agents as $agent_) {
            if ($agent_ instanceof StructureAgentForce) $agent_ = $agent_->getAgent();
            $fiches = $this->getFichePosteService()->getFichesPostesByAgent($agent_);
            $fichesDePoste[$agent_->getId()] = $fiches;
        }
        $fichesDePostePdf = $this->getAgentService()->getFichesPostesPdfByAgents($agents);

        $displayBandeau = $this->getParametreService()->getValeurForParametre(FichePosteParametres::TYPE, FichePosteParametres::DISPLAY_BANDEAU_FICHEPOSTE);
        $template = null;
        if ($displayBandeau and $this->getTemplateService()->getTemplateByCode(TextTemplates::FICHEPOSTE_BANDEAU)) {
            $template = $this->getRenduService()->generateRenduByTemplateCode(TextTemplates::FICHEPOSTE_BANDEAU, [], false);
        }

        $vm = new ViewModel([
            'agents' => $agents,
            'campagne' => $campagne,
            'fichesDePoste' => $fichesDePoste,
            'fichesDePostePdf' => $fichesDePostePdf,

            'displayBandeau' => $displayBandeau,
            'template' => $template,
        ]);
        return $vm;
    }

    public function mesEntretiensProfessionnelsAction(): ViewModel
    {
        $campagne = $this->getCampagneService()->getRequestedCampagne($this);
        if ($campagne === null) $campagne = $this->getCampagneService()->getBestCampagne();

        $role = $this->getUserService()->getConnectedRole();
        $connectedAgent = $this->getAgentService()->getAgentByConnectedUser();

        $agents = [];
        $entretiensS = [];
        $entretiensR = [];
        if ($role->getRoleId() === AgentRoleProvider::ROLE_SUPERIEURE) {
            $agents = $this->getAgentSuperieurService()->getAgentsWithSuperieur($connectedAgent, $campagne->getDateEnPoste(), $campagne->getDateFin());
            $entretiensS = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agents, false);
            $entretiensR = $this->getEntretienProfessionnelService()->getEntretiensProfessionnelsByResponsableAndCampagne($connectedAgent, $campagne, false, false);
        }
        if ($role->getRoleId() === AgentRoleProvider::ROLE_AUTORITE) {
            $agents = $this->getAgentAutoriteService()->getAgentsWithAutorite($connectedAgent, $campagne->getDateEnPoste(), $campagne->getDateFin());
            $entretiensS = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agents, false);
            $entretiensR = [];
        }

        $entretiens = [];
        foreach ($entretiensR as $entretien) {
            $entretiens[$entretien->getAgent()->getId()] = $entretien;
        }
        foreach ($entretiensS as $entretien) {
            $entretiens[$entretien->getAgent()->getId()] = $entretien;
        }

        //Extraction de la liste des campagnes
        $campagnes = $this->getCampagneService()->getCampagnes();

        //manque le tri des agents !!!!
        [$obligatoires, $facultatifs, $raisons, $exclus] = $this->getCampagneService()->trierAgents($campagne, $agents);

        $entretiens = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $agents, false);
        $finalises = [];
        $encours = [];
        foreach ($entretiens as $entretien) {
            if ($entretien->isEtatActif(EntretienProfessionnelEtats::ENTRETIEN_VALIDATION_AGENT)) {
                $finalises[] = $entretien;
            } else {
                $encours[] = $entretien;
            }
        }

        /** GENERATION DES CONTENUS TEMPLATISÉS ***********************************************************************/
        $vars = ['UrlService' => $this->getUrlService(), 'campagne' => $campagne];
        $templates = [];
        $templates[TexteTemplates::EP_EXPLICATION_SANS_OBLIGATION] = $this->getRenduService()->generateRenduByTemplateCode(TexteTemplates::EP_EXPLICATION_SANS_OBLIGATION, $vars, false);

        $vm = new ViewModel([
            'campagnes' => $campagnes,
            'campagne' => $campagne,
            'agent' => $connectedAgent,
            'agents' => $agents,
            'obligatoires' => $obligatoires,
            'facultatifs' => $facultatifs,
            'exclus' => $exclus,
            'raisons' => $raisons,

            'entretiens' => $entretiens,
            'encours' => $encours,
            'finalises' => $finalises,

            'templates' => $templates,
        ]);
        return $vm;
    }

    /** @return Agent[] */
    public function listerAgents(): array
    {
        $role = $this->getUserService()->getConnectedRole();
        $connectedAgent = $this->getAgentService()->getAgentByConnectedUser();

        $agents = [];
        if ($role->getRoleId() === AgentRoleProvider::ROLE_SUPERIEURE) {
            $chaines = $this->getAgentSuperieurService()->getAgentsSuperieursBySuperieur($connectedAgent);
            $agents = array_map(function (AgentSuperieur $a) {
                return $a->getAgent();
            }, $chaines);
        }
        if ($role->getRoleId() === AgentRoleProvider::ROLE_AUTORITE) {
            $chaines = $this->getAgentAutoriteService()->getAgentsAutoritesByAutorite($connectedAgent);
            $agents = array_map(function (AgentAutorite $a) {
                return $a->getAgent();
            }, $chaines);
        }

        $result = [];
        foreach ($agents as $agent) {
            $result[$agent->getId()] = $agent;
        }
        return $result;
    }

}