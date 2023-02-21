<?php

namespace Structure\Controller;

use Application\Entity\Db\Agent;
use Application\Entity\Db\FichePoste;
use Application\Entity\Db\Interfaces\HasSourceInterface;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueFormAwareTrait;
use Application\Form\HasDescription\HasDescriptionFormAwareTrait;
use Application\Form\SelectionAgent\SelectionAgentFormAwareTrait;
use Application\Provider\Etat\FichePosteEtats;
use Application\Service\Agent\AgentServiceAwareTrait;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\FicheProfil\FicheProfilServiceAwareTrait;
use Application\Service\SpecificitePoste\SpecificitePosteServiceAwareTrait;
use DateTime;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\Delegue\DelegueServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Formation\Entity\Db\Formation;
use Formation\Service\DemandeExterne\DemandeExterneServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Structure\Entity\Db\StructureAgentForce;
use Structure\Service\Structure\StructureServiceAwareTrait;
use Structure\Service\StructureAgentForce\StructureAgentForceServiceAwareTrait;
use UnicaenApp\View\Model\CsvModel;
use UnicaenDbImport\Entity\Db\Service\Source\SourceServiceAwareTrait;
use UnicaenDbImport\Entity\Db\Source;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;
use UnicaenPdf\Exporter\PdfExporter;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class StructureController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use AgentMissionSpecifiqueServiceAwareTrait;
    use DemandeExterneServiceAwareTrait;
    use EtatServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use FicheProfilServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use StructureServiceAwareTrait;
    use StructureAgentForceServiceAwareTrait;
    use SpecificitePosteServiceAwareTrait;
    use UserServiceAwareTrait;
    use SourceServiceAwareTrait;

    use CampagneServiceAwareTrait;
    use DelegueServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;


    use AgentMissionSpecifiqueFormAwareTrait;
    use SelectionAgentFormAwareTrait;
    use HasDescriptionFormAwareTrait;


    private $renderer;

    public function setRenderer($renderer)
    {
        $this->renderer = $renderer;
    }

    public function indexAction() : ViewModel
    {
        $structures = $this->getStructureService()->getStructures();

        return new ViewModel([
            'structures' => $structures,
        ]);
    }

    public function afficherAction() : ViewModel
    {
        $role = $this->getUserService()->getConnectedRole();
        $utilisateur = $this->getUserService()->getConnectedUser();
        $selecteur = $this->getStructureService()->getStructuresByCurrentRole($utilisateur, $role);

        /** Récupération des structures */
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] =  $structure;

        /** Récupération des missions spécifiques liées aux structures */
        $missionsSpecifiques = $this->getAgentMissionSpecifiqueService()->getAgentMissionsSpecifiquesByStructures($structures, false);

        /** Récupération des agents et postes liés aux structures */
        $agents = $this->getAgentService()->getAgentsByStructures($structures);
        $agentsForces = $this->getStructureService()->getAgentsForces($structure);
        $agentsForces = array_map(function (StructureAgentForce $a) { return $a->getAgent(); }, $agentsForces);
        $allAgents = array_merge($agents, $agentsForces);

        $superieurs = []; $autorites = [];
        foreach ($allAgents as $agent) {
            $sup = $this->getAgentService()->computeSuperieures($agent);
            $aut = $this->getAgentService()->computeAutorites($agent, $sup);
            $superieurs[$agent->getId()] = $sup;
            $autorites[$agent->getId()] = $aut;
        }

//        $fichesRecrutements = $this->getStructureService()->getFichesPostesRecrutementsByStructures($structures);
        usort($allAgents, function (Agent $a, Agent $b) { $aaa = $a->getNomUsuel() . " ". $a->getPrenom(); $bbb = $b->getNomUsuel() . " ". $b->getPrenom(); return $aaa > $bbb;});

        /** Campagne */
        $last =  $this->getCampagneService()->getLastCampagne();

        /** Récupération des agents et postes liés aux structures */
        $agentsLast = ($last !== null)?$this->getAgentService()->getAgentsByStructures($structures, $last->getDateDebut()):[];
        $agentsForcesLast = array_map(function (StructureAgentForce $a) { return $a->getAgent(); }, $structure->getAgentsForces());
        $allAgentsLast = array_merge($agentsLast, $agentsForcesLast);
        $entretiensLast = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($last, $allAgentsLast);


        $campagnes =  $this->getCampagneService()->getCampagnesActives();
        $entretiensArray = [];
        foreach ($campagnes as $campagne) {
            $entretiens = $this->getEntretienProfessionnelService()->getEntretienProfessionnelByCampagneAndAgents($campagne, $allAgents);
            $entretiensArray[$campagne->getId()] = $entretiens;
        }

        $delegues = $this->getDelegueService()->getDeleguesByStructure($structure);
//        $profils = $this->getFicheProfilService()->getFichesPostesByStructure($structure);

        $fichespostes_pdf = $this->getAgentService()->getFichesPostesPdfByAgents($allAgents);

        //formations
        $demandesNonValidees =  $this->getDemandeExterneService()->getDemandesExternesNonValideesByAgents($allAgents, Formation::getAnnee());
        $demandesValidees =  $this->getDemandeExterneService()->getDemandesExternesValideesByAgents($allAgents, Formation::getAnnee());
        $inscriptionsNonValidees = $this->getFormationInstanceInscritService()->getInscriptionsNonValideesByAgents($allAgents, Formation::getAnnee());
        $inscriptionsValidees = $this->getFormationInstanceInscritService()->getInscriptionsValideesByAgents($allAgents, Formation::getAnnee());

        return new ViewModel([
            'selecteur' => $selecteur,

            'structure' => $structure,
            'responsables' => $this->getStructureService()->getResponsables($structure, new DateTime()),
            'gestionnaires' => $this->getStructureService()->getGestionnaires($structure, new DateTime()),
            'filles' =>   $structure->getEnfants(),

            'missions' => $missionsSpecifiques,
            'fichespostes' => $this->getFichePosteService()->getFichesPostesbyAgents($allAgents),
            'fichespostes_pdf' => $fichespostes_pdf,
            'fichePosteEtats' => $this->getEtatService()->getEtatsByTypeCode(FichePosteEtats::TYPE),

            'agents' => $agents,
            'agentsForces' => $agentsForces,
            'agentsAll' => $allAgents,
            'superieurs' => $superieurs,
            'autorites' => $autorites,

            'last' => $last,
            'agentsLast' => $allAgentsLast,
            'entretiensLast' => $entretiensLast,
            'campagnes' => $campagnes,
            'entretiensArray' => $entretiensArray,
            'delegues' => $delegues,

            //formations
            'demandesNonValidees' => $demandesNonValidees,
            'demandesValidees' => $demandesValidees,
            'inscriptionsNonValidees' => $inscriptionsNonValidees,
            'inscriptionsValidees' => $inscriptionsValidees,

            // - Fiches de recrutement - //
//            'fichesRecrutements' => $fichesRecrutements,
//            'profils' => $profils,
        ]);
    }

    /** RESUME ********************************************************************************************************/

    public function editerDescriptionAction() : ViewModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $form = $this->getHasDescriptionForm();
        $form->setAttribute('action', $this->url()->fromRoute('structure/editer-description', ['structure' => $structure->getId()], [] , true));
        $form->bind($structure);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getStructureService()->update($structure);
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => 'Édition de la description de la structure',
            'form' => $form,
        ]);
        return $vm;
    }

    public function toggleResumeMereAction() : Response
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $structure->setRepriseResumeMere(! $structure->getRepriseResumeMere());
        $this->getStructureService()->update($structure);

        return $this->redirect()->toRoute('structure/afficher', ['structure' => $structure->getId()], [], true);
    }

    /** Fonctions de recherche ****************************************************************************************/

    public function rechercherAction() : JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $structures = $this->getStructureService()->getStructuresByTerm($term);
            $result = $this->getStructureService()->formatStructureJSON($structures);
            return new JsonModel($result);
        }
        exit;
    }

    public function rechercherWithStructureMereAction() : JsonModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;

        $term = $this->params()->fromQuery('term');
        if ($term) {
            $structures = $this->getStructureService()->getStructuresByTerm($term, $structures);
            $result = $this->getStructureService()->formatStructureJSON($structures);
            return new JsonModel($result);
        }
        exit;
    }

    /** AGENTS FORCES *************************************************************************************************/

    public function ajouterManuellementAgentAction() : ViewModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $structureAgentForce = new StructureAgentForce();
        $structureAgentForce->setStructure($structure);

        $form = $this->getSelectionAgentForm();
        $form->get('agent')->setAutocompleteSource($this->url()->fromRoute('agent/rechercher-large', [], [], true));
        $form->setAttribute('action',$this->url()->fromRoute('structure/ajouter-manuellement-agent', ['structure' => $structure->getId()], [], true));
        $form->bind($structureAgentForce);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                if ($this->getStructureAgentForceService()->getStructureAgentForceByStructureAndAgent($structureAgentForce->getStructure(), $structureAgentForce->getAgent()) === null) {
                    $this->getStructureAgentForceService()->create($structureAgentForce);
                }
            }
        }

        $vm = new ViewModel();
        $vm->setTemplate('application/default/default-form');
        $vm->setVariables([
            'title' => "Forcer manuellement l'ajout d'un agent",
            'form' => $form,
        ]);
        return $vm;
    }

    public function retirerManuellementAgentAction() : ViewModel
    {
        $agent = $this->getAgentService()->getRequestedAgent($this);
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $structureAgentForce = $this->getStructureAgentForceService()->getStructureAgentForceByStructureAndAgent($structure, $agent);

        /** @var Request $request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") $this->getStructureAgentForceService()->delete($structureAgentForce);
            exit();
        }

        $vm = new ViewModel();
//        if ($structureAgentForce !== null) {
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Retirer [".$structureAgentForce->getAgent()->getDenomination()."] de la structure [" . $structureAgentForce->getStructure()->getLibelleCourt() . "]",
                'text' => "Le retrait est définitif, êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('structure/retirer-manuellement-agent', ["structure" => $structure->getId(), "agent" => $agent->getId()], [], true),
            ]);
//        }
        return $vm;
    }

    /** FiCHE DE POSTE RECRUTEMENT ************************************************************************************/

    public function ajouterFichePosteRecrutementAction() : Response
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $fiche = new FichePoste();
        $this->getFichePosteService()->create($fiche);

        $structure->addFichePosteRecrutement($fiche);
        $this->getStructureService()->update($structure);

        /** @see FichePosteController::editerAction() */
        return $this->redirect()->toRoute('fiche-poste/editer', ['fiche-poste' => $fiche->getId()], ["query" => ["structure" => $structure->getId()]], true);
    }

    public function dupliquerFichePosteRecrutementAction() : ViewModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $fiche = $this->getFichePosteService()->getFichePoste($data['fiche']);

            if ($fiche != null) {
                $nouvelleFiche = $this->getFichePosteService()->clonerFichePoste($fiche, true);

                $structure->addFichePosteRecrutement($nouvelleFiche);
                $this->getStructureService()->update($structure);
            }


        }

        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;
        $fichesPostesAgent = $this->getFichePosteService()->getFichesPostesByStructures($structures);
        $fichesPostesRecrutement = $structure->getFichesPostesRecrutements();

        $fichespostes = [];
        foreach ($fichesPostesAgent as $fichePoste) $fichespostes[$fichePoste->getId()] = $fichePoste;
        foreach ($fichesPostesRecrutement as $fichePoste) $fichespostes[$fichePoste->getId()] = $fichePoste;

        $vm =  new ViewModel([
            'title' => 'Sélectionner la fiche de poste à dupliquer',
            'fichespostes' => $fichespostes,
            'url' => $this->url()->fromRoute('structure/dupliquer-fiche-poste-recrutement', ['structure' => $structure->getId()], [], true),
        ]);
        $vm->setTemplate('structure/structure/dupliquer-fiche-poste');
        return $vm;
    }

    /** EXTRACTIONS ***************************************************************************************************/

    /**
     * Extraction du listing des agents et des fiches de poste associées
     */
    public function extractionListingFichePosteAction() : CsvModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;
        $agents = $this->getAgentService()->getAgentsByStructures($structures);

        $filename = "listing_fiche_poste_-_" . str_replace(" ","_",$structure->getLibelleCourt()) . "_-_" . (new DateTime())->format('ymd-hms') . ".csv";
        $header = ["Agent", "Fiche metier principale", "Complement"];

        $result = [];
        foreach ($agents as $agent) {
            $denomination = $agent->getDenomination();
            $fiche = "";
            $complement = "";

            $ficheposte = $this->getFichePosteService()->getFichePosteActiveByAgent($agent);
            if ($ficheposte !== null) {
                $fiche = $ficheposte->getLibelleMetierPrincipal(FichePoste::TYPE_GENRE);
                $complement = $ficheposte->getLibelle();
            }

            $result[] = [$denomination, $fiche, $complement];
        }

        $CSV = new CsvModel();
        $CSV->setDelimiter(';');
        $CSV->setEnclosure('"');
        $CSV->setHeader($header);
        $CSV->setData($result);
        $CSV->setFilename($filename);
        return $CSV;

    }

    /**
     * Extraction du listing des agents et des fiches de poste associées
     */
    public function extractionListingMissionSpecifiqueAction() : CsvModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $structures = $this->getStructureService()->getStructuresFilles($structure);
        $structures[] = $structure;
        $missionsSpecifiques = $this->getAgentMissionSpecifiqueService()->getAgentMissionsSpecifiquesByStructures($structures, false);


        $filename = "listing_mission_specifique-_" . str_replace(" ","_",$structure->getLibelleCourt()) . "_-_" . (new DateTime())->format('ymd-hms') . ".csv";
        $header = ["Agent", "Structure", "Mission", "Début de mission", "Fin de mission", "Volume"];

        $result = [];
        foreach ($missionsSpecifiques as $mission) {
            $agent = $mission->getAgent()->getDenomination();
            $structure = $mission->getStructure()->getLibelleLong();
            $libelle = $mission->getMission()->getLibelle();
            $debut = ($mission->getDateDebut())?$mission->getDateDebut()->format('d/m/Y'):"";
            $fin = ($mission->getDateFin())?$mission->getDateFin()->format('d/m/Y'):"";
            $volume = $mission->getDecharge();
            $result[] = [$agent, $structure, $libelle, $debut, $fin, $volume];
        }

        $CSV = new CsvModel();
        $CSV->setDelimiter(';');
        $CSV->setEnclosure('"');
        $CSV->setHeader($header);
        $CSV->setData($result);
        $CSV->setFilename($filename);
        return $CSV;
    }

    public function organigrammeAction() : ViewModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $agents[$structure->getId()] = $this->getAgentService()->getAgentsByStructures([$structure]);

        foreach ($structure->getEnfants() as $enfant) {
            $agents[$enfant->getId()] = $this->getAgentService()->getAgentsByStructures([$enfant]);
            foreach ($enfant->getEnfants() as $petitenfant) {
                $sub = $this->getStructureService()->getSousStructures($petitenfant);
                $sub[] = $petitenfant;
                $agents[$petitenfant->getId()] = $this->getAgentService()->getAgentsByStructures([$sub]);
            }
        }

        $affectationsSecondaires = $this->getStructureService()->getAgentsEnAffectationSecondaire($structure);

        $vm = new ViewModel();
        //$vm->setTemplate('blank');
        $vm->setVariables([
            'structure' => $structure,
            'agents' => $agents,
            'secondaires' => $affectationsSecondaires,
        ]);
        return $vm;
    }

    public function organigrammePdfAction() : string
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $agents[$structure->getId()] = $this->getAgentService()->getAgentsByStructures([$structure]);

        foreach ($structure->getEnfants() as $enfant) {
            $agents[$enfant->getId()] = $this->getAgentService()->getAgentsByStructures([$enfant]);
            foreach ($enfant->getEnfants() as $petitenfant) {
                $sub = $this->getStructureService()->getSousStructures($petitenfant);
                $sub[] = $petitenfant;
                $agents[$petitenfant->getId()] = $this->getAgentService()->getAgentsByStructures([$sub]);
            }
        }

        $vars = [
            'structure' => $structure,
            'agents' => $agents,
        ];

        $pdf = new PdfExporter();
        $pdf->setRenderer($this->renderer);
        $pdf->setHeaderScript('structure/structure/header.phtml');
        $pdf->setFooterScript('structure/structure/footer.phtml');
        $pdf->addBodyScript('structure/structure/organigramme.phtml', false, $vars);
        return $pdf->export("temp.pdf");
    }

}