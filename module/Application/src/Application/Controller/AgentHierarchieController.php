<?php

namespace Application\Controller;

use Application\Entity\Db\AgentAutorite;
use Application\Entity\Db\AgentSuperieur;
use Application\Form\AgentHierarchieImportation\AgentHierarchieImportationFormAwareTrait;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class AgentHierarchieController extends AbstractActionController
{
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
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

}