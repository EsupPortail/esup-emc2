<?php

namespace Application\Controller;

use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Form\AgentHierarchieCalcul\AgentHierarchieCalculFormAwareTrait;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Structure\Service\Structure\StructureServiceAwareTrait;

class AgentHierarchieController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use StructureServiceAwareTrait;
    use AgentHierarchieCalculFormAwareTrait;
    use AgentHierarchieImportationFormAwareTrait;

    public function indexAction() : ViewModel
    {
        return new ViewModel([]);
    }

    public function afficherAction() : ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $superieurs = $this->getAgentSuperieurService()->getAgentsSuperieursByAgent($agent);
        usort($superieurs, function(AgentSuperieur $a, AgentSuperieur $b) {
            return $a->getSuperieur()->getNomUsuel().' '.$a->getSuperieur()->getPrenom() > $b->getSuperieur()->getNomUsuel().' '.$b->getSuperieur()->getPrenom();});
        $autorites = $this->getAgentAutoriteService()->getAgentsAutoritesByAgent($agent, true);
        usort($autorites, function(AgentAutorite $a, AgentAutorite $b) {
            return $a->getAutorite()->getNomUsuel().' '.$a->getAutorite()->getPrenom() > $b->getAutorite()->getNomUsuel().' '.$b->getAutorite()->getPrenom();});

        return new ViewModel([
            'title' => "Chaîne hiérarchique de [".$agent->getDenomination()."]",
            'agent' => $agent,
            'superieurs' => $superieurs,
            'autorites' => $autorites,
        ]);
    }

    public function importerAction() : ViewModel
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
            if ($fichier_path === null OR $fichier_path === '') {
                $error[] = "Aucun fichier !";
            } else {
                $handle = fopen($fichier_path, "r");
                $array = [];
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

            if ($mode === 'import' AND empty($error)) {
                $warning = [];
                foreach ($array as $line) {
                    $agentId = $line[0]; $agent = $agents[$agentId];
                    $superieurs = array_slice($line, 1, 3);
                    $autorites = array_slice($line, 4, 3);
                    $this->getAgentSuperieurService()->historiseAll($agent);
                    $logS = $this->getAgentSuperieurService()->createAgentSuperieurWithArray($agent, $superieurs, $agents);
                    $this->getAgentAutoriteService()->historiseAll($agent);
                    $logA = $this->getAgentAutoriteService()->createAgentAutoriteWithArray($agent, $autorites, $agents);
                    $warning =  array_merge($warning, $logA['warning'], $logS['warning']);
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

    public function calculerAction() : ViewModel
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
            $autorites = [];
            foreach ($agents as $agent) {
                $superieurs[$agent->getId()] = $this->getAgentService()->computeSuperieures($agent);
                $autorites[$agent->getId()] = $this->getAgentService()->computeAutorites($agent, $superieurs[$agent->getId()]);
            }

            if ($mode === 'compute' AND empty($error)) {
                $warning = [];
                foreach ($agents as $agent) {
                    $this->getAgentAutoriteService()->historiseAll($agent);
                    if (empty($autorites[$agent->getId()])) {
                        $warning[] = "Aucune autorité n'a pu être déterminée pour ".$agent->getDenomination();
                    } else {
                        foreach ($autorites[$agent->getId()] as $autorite) {
                            $this->getAgentAutoriteService()->createAgentAutorite($agent, $autorite);
                        }
                    }
                    $this->getAgentSuperieurService()->historiseAll($agent);
                    if (empty($superieurs[$agent->getId()])) {
                        $warning[] = "Aucun supérieur n'a pu être déterminée pour ".$agent->getDenomination();
                    } else {
                        foreach ($superieurs[$agent->getId()] as $superieur) {
                            $this->getAgentSuperieurService()->createAgentSuperieur($agent, $superieur);
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

}