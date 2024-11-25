<?php

namespace Application\Controller;

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
use Structure\Service\Structure\StructureServiceAwareTrait;

class AgentHierarchieController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
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
        $form = $this->getAgentHierarchieImportationForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/hierarchie/importer', ['mode' => 'preview', 'path' => null], [], true));


        $request = $this->getRequest();
        if ($request->isPost()) {
            $error = [];
            $data = $request->getPost();
            $file = $request->getFiles();

            $fichier_path = $file['fichier']['tmp_name'];
            $mode = $data['mode'];

            //reading
            $array = [];
            if ($fichier_path === null or $fichier_path === '') {
                $error[] = "Aucun fichier !";
            } else {
                $handle = fopen($fichier_path, "r");

                while ($content = fgetcsv($handle, 0, ";")) {
                    $array[] = $content;
                }

                $agentsId = [];
                foreach ($array as $line) {
                    foreach ($line as $id) $agentsId[$id] = $id;
                }

                $agents = [];
                $warning = [];
                foreach ($agentsId as $agentId) {
                    if ($agentId !== '') {
                        $agent = $this->getAgentService()->getAgent($agentId);
                        if ($agent === null) {
                            $warning[] = "Aucun agent de trouvé avec l'identifiant [" . $agentId . "]";
                        } else {
                            $agents[$agentId] = $agent;
                        }
                    }
                }
            }

            if ($mode === 'import' and empty($error)) {
                $warning = [];
                foreach ($array as $line) {
                    $agentId = $line[0];
                    $agent = $agents[$agentId];
                    $superieurs = array_slice($line, 1, 3);
                    $autorites = array_slice($line, 4, 3);
                    $this->getAgentSuperieurService()->historiseAll($agent);
                    $logS = $this->getAgentSuperieurService()->createAgentSuperieurWithArray($agent, $superieurs, $agents);
                    $this->getAgentAutoriteService()->historiseAll($agent);
                    $logA = $this->getAgentAutoriteService()->createAgentAutoriteWithArray($agent, $autorites, $agents);
                    $warning = array_merge($warning, $logA['warning'], $logS['warning']);
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
                'fichier_path' => $fichier_path,
                'form' => $form,
                'mode' => $mode,
                'error' => $error,
                'warning' => $warning,
                'agents' => $agents,
                'array' => $array,
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
        $form = $this->getAgentHierarchieCalculForm();
        $form->setAttribute('action', $this->url()->fromRoute('agent/hierarchie/calculer', ['mode' => 'preview', 'structure' => null], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $error = [];
            $data = $request->getPost();

            $mode = $data['mode'];
            $structureId = $data['structure'];

            $structure = $this->getStructureService()->getStructure($structureId);
            $structures = $this->getStructureService()->getStructuresFilles($structure, true);
            $agents = $this->getAgentService()->getAgentsByStructures($structures);

            $superieurs = [];
//            $autorites = [];
            foreach ($agents as $agent) {
                $superieurs[$agent->getId()] = $this->getAgentService()->computeSuperieures($agent);
                $autorites[$agent->getId()] = $this->getAgentService()->computeAutorites($agent, $superieurs[$agent->getId()]);
            }

            $warning = [];
            if ($mode === 'compute' and empty($error)) {
                foreach ($agents as $agent) {
                    $this->getAgentAutoriteService()->historiseAll($agent);
                    if (empty($autorites[$agent->getId()])) {
                        $warning[] = "Aucune autorité n'a pu être déterminée pour " . $agent->getDenomination();
                    } else {
                        foreach ($autorites[$agent->getId()] as $autorite) {
                            $agentAutorite = new AgentAutorite();
                            $agentAutorite->setAgent($agent);
                            $agentAutorite->setAutorite($autorite->getAgent());
                            $agentAutorite->setDateDebut($autorite->getDateDebut());
                            $agentAutorite->setDateFin($autorite->getDateFin());
                            $agentAutorite->setSourceId('EMC2');

                            $id = $agentAutorite->generateId();
                            $old = $this->getAgentAutoriteService()->getAgentAutorite($id);
                            if ($old !== null) {
                                $this->getAgentAutoriteService()->restore($old);
                            } else {
                                $agentAutorite->setId($id);
                                $agentAutorite->setCreatedOn(new DateTime());
                                $this->getAgentAutoriteService()->create($agentAutorite);
                            }
                        }
                    }
                    $this->getAgentSuperieurService()->historiseAll($agent);
                    if (empty($superieurs[$agent->getId()])) {
                        $warning[] = "Aucun supérieur n'a pu être déterminée pour " . $agent->getDenomination();
                    } else {
                        foreach ($superieurs[$agent->getId()] as $superieur) {
                            $agentSuperieur = new AgentSuperieur();
                            $agentSuperieur->setAgent($agent);
                            $agentSuperieur->setSuperieur($superieur->getAgent());
                            $agentSuperieur->setDateDebut($superieur->getDateDebut());
                            $agentSuperieur->setDateFin($superieur->getDateFin());
                            $agentSuperieur->setSourceId('EMC2');

                            $id = $agentSuperieur->generateId();
                            $old = $this->getAgentSuperieurService()->getAgentSuperieur($id);
                            if ($old !== null) {
                                $this->getAgentSuperieurService()->restore($old);
                            } else {
                                $agentSuperieur->setId($id);
                                $agentSuperieur->setCreatedOn(new DateTime());
                                $this->getAgentSuperieurService()->create($agentSuperieur);
                            }
                        }
                    }
                }
            }


            if ($mode !== 'compute') {
                $title = "Calcul de chaînes hiérarchiques (Prévisualisation)";
            }
            if ($mode === 'compute') {
                $title = "Calcul de chaînes hiérarchiques (Importation)";
            }
            return new ViewModel([
                'title' => $title,
                'form' => $form,
                'structure' => $structure,
                'agents' => $agents,
                'superieurs' => $superieurs,
                'autorites' => $autorites,
                'error' => $error,
                'warning' => $warning,
            ]);
        }

        return new ViewModel([
            'title' => "Calcul de chaînes hiérarchiques",
            'form' => $form,
        ]);
    }

    public function chaineHierarchiqueJsonAction(): JsonModel
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

    public function rechercherAgentWithAutoriteAction(): JsonModel
    {
        $superieur = $this->getAgentService()->getAgentByConnectedUser();

        if ($superieur !== null and ($term = $this->params()->fromQuery('term'))) {
            $agents = $this->getAgentAutoriteService()->getAgentsWithAutoriteAndTerm($superieur, $term);
            $result = $this->getAgentService()->formatAgentJSON($agents);
            return new JsonModel($result);
        }
        exit();
    }

    public function rechercherAgentWithSuperieurAction(): JsonModel
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
        $form->setAttribute('action', $this->url()->fromRoute('agent/hierarchie/ajouter', ['agent' => $agent->getId(), 'type' => $type], [], true));
        $form->bind($chaine);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $id = $chaine->generateId();
                $chaine->setId($id);
                $chaine->setCreatedOn(new DateTime());
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
            'js' => ($agent)?"$('#agent-autocomplete').parent().hide();":"",
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierAction(): ViewModel
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
            'js' => ($chaine->getAgent())?"$('#agent-autocomplete').parent().hide();":"",
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function historiserAction(): Response
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

    public function restaurerAction(): Response
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

    public function supprimerAction(): ViewModel
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
}