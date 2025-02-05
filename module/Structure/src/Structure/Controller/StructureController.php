<?php

namespace Structure\Controller;

use Application\Controller\AgentController;
use Application\Entity\Db\Agent;
use Application\Entity\Db\FichePoste;
use Application\Form\AgentMissionSpecifique\AgentMissionSpecifiqueFormAwareTrait;
use Application\Form\HasDescription\HasDescriptionFormAwareTrait;
use Application\Form\SelectionAgent\SelectionAgentFormAwareTrait;
use Application\Service\AgentAutorite\AgentAutoriteServiceAwareTrait;
use Application\Service\AgentSuperieur\AgentSuperieurServiceAwareTrait;
use FichePoste\Provider\Etat\FichePosteEtats;
use Application\Provider\Parametre\GlobalParametres;
use Application\Service\Agent\AgentServiceAwareTrait;
use Agent\Service\AgentAffectation\AgentAffectationServiceAwareTrait;
use Application\Service\AgentMissionSpecifique\AgentMissionSpecifiqueServiceAwareTrait;
use Application\Service\FichePoste\FichePosteServiceAwareTrait;
use Application\Service\SpecificitePoste\SpecificitePosteServiceAwareTrait;
use DateTime;
use EntretienProfessionnel\Entity\Db\Campagne;
use EntretienProfessionnel\Service\Campagne\CampagneServiceAwareTrait;
use EntretienProfessionnel\Service\EntretienProfessionnel\EntretienProfessionnelServiceAwareTrait;
use Exception;
use Laminas\Http\Request;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Laminas\View\Renderer\PhpRenderer;
use Mpdf\MpdfException;
use RuntimeException;
use Structure\Entity\Db\StructureAgentForce;
use Structure\Provider\Parametre\StructureParametres;
use Structure\Service\Observateur\ObservateurServiceAwareTrait;
use Structure\Service\Structure\StructureServiceAwareTrait;
use Structure\Service\StructureAgentForce\StructureAgentForceServiceAwareTrait;
use UnicaenApp\View\Model\CsvModel;
use UnicaenEtat\Service\EtatType\EtatTypeServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenPdf\Exporter\PdfExporter;

class StructureController extends AbstractActionController {
    use AgentServiceAwareTrait;
    use AgentAutoriteServiceAwareTrait;
    use AgentAffectationServiceAwareTrait;
    use AgentMissionSpecifiqueServiceAwareTrait;
    use AgentSuperieurServiceAwareTrait;
    use EtatTypeServiceAwareTrait;
    use FichePosteServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use StructureServiceAwareTrait;
    use StructureAgentForceServiceAwareTrait;
    use ObservateurServiceAwareTrait;
    use SpecificitePosteServiceAwareTrait;

    use CampagneServiceAwareTrait;
    use EntretienProfessionnelServiceAwareTrait;


    use AgentMissionSpecifiqueFormAwareTrait;
    use SelectionAgentFormAwareTrait;
    use HasDescriptionFormAwareTrait;


    private PhpRenderer $renderer;

    public function setRenderer(PhpRenderer $renderer): void
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

    public function descriptionAction() : ViewModel
    {
        $selecteur = $this->getStructureService()->getStructuresByCurrentRole();

        $structure = $this->getStructureService()->getRequestedStructure($this);
        $responsables = $this->getStructureService()->getResponsables($structure, new DateTime());
        $gestionnaires = $this->getStructureService()->getGestionnaires($structure, new DateTime());
        $observateurs = $this->getObservateurService()->getObservateursByStructures([$structure]);

        $blocGestionnaire = $this->getParametreService()->getValeurForParametre(StructureParametres::TYPE, StructureParametres::BLOC_GESTIONNAIRE);
        $blocObservateur = $this->getParametreService()->getValeurForParametre(StructureParametres::TYPE, StructureParametres::BLOC_OBSERVATEUR);

        $niveau2 = $structure->getNiv2();
        $parent = $structure->getParent();
        $filles = $structure->getEnfants();

        $last =  $this->getCampagneService()->getLastCampagne();
        $campagnes =  $this->getCampagneService()->getCampagnesActives();
        if ($last !== null) $campagnes[] = $last;
        usort($campagnes, function (Campagne $a, Campagne $b) { return $a->getDateDebut() <=> $b->getDateDebut();});

        return new ViewModel([
            'selecteur' => $selecteur,
            'structure' => $structure,
            'responsables' => $responsables,
            'gestionnaires' => $gestionnaires,
            'observateurs' => $observateurs,

            'niveau2' => $niveau2,
            'parent' => $parent,
            'filles' => $filles,

            'campagnes' => $campagnes,

            'blocGestionnaire' => $blocGestionnaire,
            'blocObservateur' => $blocObservateur,
        ]);
    }

    public function agentsAction() : ViewModel
    {
        $debug = "";

        $date_debut = new DateTime();
        $selecteur = $this->getStructureService()->getStructuresByCurrentRole();
        $debug .= "Récupération des informations de l'utilisateur : ".((new DateTime())->diff($date_debut))->format('%i minutes, %s secondes, %f microsecondes')  . "<br>";

        $structure = $this->getStructureService()->getRequestedStructure($this);
        $structures = $this->getStructureService()->getStructuresFilles($structure, true);
        $debug .= "Récupération des informations des structures : ".((new DateTime())->diff($date_debut))->format('%i minutes, %s secondes, %f microsecondes')  . "<br>";

        $agents = $this->getAgentService()->getAgentsByStructures($structures,null, true);
        $debug .= "Récupération des informations des agents : ".((new DateTime())->diff($date_debut))->format('%i minutes, %s secondes, %f microsecondes')  . "<br>";
        [$conserver, $retirer, $raison] = $this->getStructureService()->trierAgents($agents);
        $debug .= "Filtrage des agents : ".((new DateTime())->diff($date_debut))->format('%i minutes, %s secondes, %f microsecondes')  . "<br>";
        $agentsForces = $this->getStructureService()->getAgentsForces($structure);

        usort($agents, function (Agent $a, Agent $b) {
           $aaa = ($a->getNomUsuel()??$a->getNomFamille())." ".$a->getPrenom();
           $bbb = ($b->getNomUsuel()??$b->getNomFamille())." ".$b->getPrenom();
           return $aaa <=> $bbb;
        });

        $affectations = $this->getAgentAffectationService()->getAgentsAffectationsByAgents($agents);
        $superieurs = $this->getAgentSuperieurService()->getAgentsSuperieursByAgents($agents);
        $autorites = $this->getAgentAutoriteService()->getAgentsAutoritesByAgents($agents);

        $last =  $this->getCampagneService()->getLastCampagne();
        $campagnes =  $this->getCampagneService()->getCampagnesActives();
        if ($last !== null) $campagnes[] = $last;
        usort($campagnes, function (Campagne $a, Campagne $b) { return $a->getDateDebut() <=> $b->getDateDebut();});

        try {
            $emailAssistance = $this->getParametreService()->getValeurForParametre(GlobalParametres::TYPE, GlobalParametres::EMAIL_ASSISTANCE);
        } catch (Exception $e) {
            throw new RuntimeException("Une erreur est survenu lors de la récupération du paramètre [".GlobalParametres::TYPE."|".GlobalParametres::EMAIL_ASSISTANCE."]",0,$e);
        }
        $debug .= "Fin du traitement côté controller : ".((new DateTime())->diff($date_debut))->format('%i minutes, %s secondes, %f microsecondes')  . "<br>";

        return new ViewModel([
            'structure' => $structure,
            'selecteur' => $selecteur,
            'agents' => $conserver,
            'agentsRetires' => $retirer,
            'raison' => $raison,
            'agentsForces' => $agentsForces,

            'affectations' => $affectations,
            'superieurs' => $superieurs,
            'autorites' => $autorites,

            'campagnes' => $campagnes,
            'emailAssistance' => $emailAssistance,
            'debug' => null,
        ]);
    }

    public function missionsSpecifiquesAction() : ViewModel
    {
        $selecteur = $this->getStructureService()->getStructuresByCurrentRole();

        $structure = $this->getStructureService()->getRequestedStructure($this);
        $structures = $this->getStructureService()->getStructuresFilles($structure, true);

        $missionsSpecifiques = $this->getAgentMissionSpecifiqueService()->getAgentMissionsSpecifiquesByStructures($structures, false);

        $last =  $this->getCampagneService()->getLastCampagne();
        $campagnes =  $this->getCampagneService()->getCampagnesActives();
        if ($last !== null) $campagnes[] = $last;
        usort($campagnes, function (Campagne $a, Campagne $b) { return $a->getDateDebut() <=> $b->getDateDebut();});

        return new ViewModel([
            'structure' => $structure,
            'selecteur' => $selecteur,
            'missionsSpecifiques' => $missionsSpecifiques,
            'campagnes' => $campagnes,
        ]);
    }

    public function fichesDePosteAction() : ViewModel
    {
        $selecteur = $this->getStructureService()->getStructuresByCurrentRole();

        $structure = $this->getStructureService()->getRequestedStructure($this);
        $structures = $this->getStructureService()->getStructuresFilles($structure, true);

        $agents = $this->getAgentService()->getAgentsByStructures($structures);
        [$conserver, $retirer, $raison] = $this->getStructureService()->trierAgents($agents);
        $agentsForces = array_map(function (StructureAgentForce $a) { return $a->getAgent(); }, $this->getStructureService()->getAgentsForces($structure));

        $allAgents = array_merge($conserver, $agentsForces);

        usort($allAgents, function (Agent $a, Agent $b) {
            $aaa = ($a->getNomUsuel()??$a->getNomFamille())." ".$a->getPrenom();
            $bbb = ($b->getNomUsuel()??$b->getNomFamille())." ".$b->getPrenom();
            return $aaa <=> $bbb;
        });

        $fichesDePoste = [];
        foreach ($allAgents as $agent) {
            if ($agent instanceof StructureAgentForce) $agent = $agent->getAgent();
            $fiches = $this->getFichePosteService()->getFichesPostesByAgent($agent);
            $fichesDePoste[$agent->getId()] = $fiches;
        }
        $fichesDePostePdf = $this->getAgentService()->getFichesPostesPdfByAgents($allAgents);

        $last =  $this->getCampagneService()->getLastCampagne();
        $campagnes =  $this->getCampagneService()->getCampagnesActives();
        if ($last !== null) $campagnes[] = $last;
        usort($campagnes, function (Campagne $a, Campagne $b) { return $a->getDateDebut() <=> $b->getDateDebut();});

        return new ViewModel([
            'structure' => $structure,
            'selecteur' => $selecteur,
            'campagnes' => $campagnes,

            'agents' => $allAgents,
            'fichesDePoste' => $fichesDePoste,
            'fichesDePostePdf' => $fichesDePostePdf,
            'etats' => $this->getEtatTypeService()->getEtatsTypesByCategorieCode(FichePosteEtats::TYPE),
        ]);
    }

    public function extractionsAction() : ViewModel
    {
        $structure = $this->getStructureService()->getRequestedStructure($this);
        $selecteur = $this->getStructureService()->getStructuresByCurrentRole();

        $last =  $this->getCampagneService()->getLastCampagne();
        $campagnes =  $this->getCampagneService()->getCampagnesActives();
        if ($last !== null) $campagnes[] = $last;
        usort($campagnes, function (Campagne $a, Campagne $b) { return $a->getDateDebut() <=> $b->getDateDebut();});

        return new ViewModel([
            'structure' => $structure,
            'selecteur' => $selecteur,
            'campagnes' => $campagnes,
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

        $retour = $this->params()->fromQuery('retour');
        if ($retour !== null) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('structure/description', ['structure' => $structure->getId()], [], true);
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
        /** @see AgentController::rechercherLargeAction() */
        $form->get('agent-sas')->setAutocompleteSource($this->url()->fromRoute('agent/rechercher-large', [], [], true));
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

        $vm = new ViewModel([
            'title' => "Forcer manuellement l'ajout d'un agent",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
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
            $vm->setTemplate('application/default/confirmation');
            $vm->setVariables([
                'title' => "Retirer [".$structureAgentForce->getAgent()->getDenomination()."] de la structure [" . $structureAgentForce->getStructure()->getLibelleCourt() . "]",
                'text' => "Le retrait est définitif, êtes-vous sûr&middot;e de vouloir continuer ?",
                'action' => $this->url()->fromRoute('structure/retirer-manuellement-agent', ["structure" => $structure->getId(), "agent" => $agent->getId()], [], true),
            ]);
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

        try {
            $pdf = new PdfExporter();
            $pdf->setRenderer($this->renderer);
            $pdf->setHeaderScript('structure/structure/header.phtml');
            $pdf->setFooterScript('structure/structure/footer.phtml');
            $pdf->addBodyScript('structure/structure/organigramme.phtml', false, $vars);
            return $pdf->export("temp.pdf");
        } catch (MpdfException $e) {
            throw new RuntimeException("Un problème est survenu lors la génération du PDF",0,$e);
        }
    }

}