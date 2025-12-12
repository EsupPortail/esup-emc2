<?php

namespace Application\Controller;

use Agent\Service\AgentRef\AgentRefServiceAwareTrait;
use Application\Entity\Db\Agent;
use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Form\AgentHierarchieCalcul\AgentHierarchieCalculFormAwareTrait;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationFormAwareTrait;
use Application\Form\Chaine\ChaineFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use DateTime;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use RuntimeException;
use Structure\Entity\Db\StructureResponsable;
use Structure\Service\Structure\StructureServiceAwareTrait;
use UnicaenApp\View\Model\CsvModel;

class AgentHierarchieController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentRefServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use StructureServiceAwareTrait;
    use AgentHierarchieCalculFormAwareTrait;
    use AgentHierarchieImportationFormAwareTrait;
    use ChaineFormAwareTrait;

    public function indexAction(): ViewModel
    {
        return new ViewModel([]);
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
                        case Agent::ROLE_SUPERIEURE :
                            $this->getAgentSuperieurService()->historiseAll($agent);
                            break;
                        case Agent::ROLE_AUTORITE :
                            $this->getAgentAutoriteService()->historiseAll($agent);
                            break;
                        default:
                            throw new RuntimeException("Type de responsabilité [" . $type . "] inconnu");
                    }
                }
                foreach ($chaines as $chaine) {
                    switch ($type) {
                        case Agent::ROLE_SUPERIEURE :
                            $this->getAgentSuperieurService()->createAgentSuperieurWithArray($chaine);
                            break;
                        case Agent::ROLE_AUTORITE :
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
            if ($type === Agent::ROLE_SUPERIEURE) {
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
            if ($type === Agent::ROLE_AUTORITE) {

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
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $chaine->getAgent()->getId()], ['fragment' => 'informations'], true);
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
        return $this->redirect()->toRoute('agent/afficher', ['agent' => $chaine->getAgent()->getId()], ['fragment' => 'informations'], true);
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

        $header = ["agent", $type, "debut", "fin"];
        $data = [];
        foreach ($chaines as $chaine) {
            $data[] =
                [
                    $chaine->getAgent()->getDenomination(),
                    match ($type) {
                        'superieur' => $chaine->getSuperieur()->getDenomination(),
                        'autorite' => $chaine->getAutorite()->getDenomination(),
                        default => null,
                    },
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


}