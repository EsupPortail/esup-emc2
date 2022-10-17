<?php

namespace Formation\Controller;

use Application\Entity\Db\Agent;
use DateTime;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationElement;
use Formation\Entity\Db\FormationGroupe;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\FormationInstanceFrais;
use Formation\Entity\Db\FormationInstanceInscrit;
use Formation\Entity\Db\LAGAFStagiaire;
use Formation\Entity\Db\Presence;
use Formation\Entity\Db\Seance;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceFrais\FormationInstanceFraisServiceAwareTrait;
use Formation\Service\FormationInstanceInscrit\FormationInstanceInscritServiceAwareTrait;
use Formation\Service\HasFormationCollection\HasFormationCollectionServiceAwareTrait;
use Formation\Service\Presence\PresenceAwareTrait;
use Formation\Service\Seance\SeanceServiceAwareTrait;
use Formation\Service\Stagiaire\StagiaireServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenDbImport\Entity\Db\Source;
use UnicaenEtat\Service\Etat\EtatServiceAwareTrait;

class ImportationLagafController extends AbstractActionController {
    use EtatServiceAwareTrait;
    use FormationServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use SeanceServiceAwareTrait;
    use FormationInstanceInscritServiceAwareTrait;
    use FormationInstanceFraisServiceAwareTrait;
    use PresenceAwareTrait;
    use StagiaireServiceAwareTrait;
    use HasFormationCollectionServiceAwareTrait;

    public Source $sourceLagaf;

    public function indexAction()
    {
        return new ViewModel();
    }

    /** IMPORT SANS REMPLACEMENT **************************************************************************************/

    public function actionAction()
    {
        $report = "";
        $formations = [];
        $groupes = [];
        $themes = [];

        $file_path = "/tmp/TAction.csv";
        $handle = fopen($file_path, "r");
        $array = [];
        while ($content = fgetcsv ( $handle, 0, ";" , '"')) {
            $array[] = $content;
        }

        $nbLine = count($array);
        $report .= "Nombre de ligne dans le fichier CSV : " . $nbLine . "\n";

        $position_id            = array_search('NAction', $array[0]);
        $position_libelle       = array_search('Action', $array[0]);
        $position_groupe        = array_search('Domaine', $array[0]);
        $position_description   = array_search('Objectifs', $array[0]);


        $report .= "<table class='table table-condensed'>";
        $report .= "<thead><tr><th>Id</th><th>Libelle</th><th>Groupe</th><th>Description</th></tr></thead>";
        $report .= "<tbody>";
        for($position = 1 ; $position < $nbLine; $position++) {
              $data = $array[$position];
              $st_id = $data[$position_id];
              $st_libelle = $data[$position_libelle];
              $st_groupe = $data[$position_groupe];
              $st_description = $data[$position_description];

              $report .= "<tr>";
              $report .= "<td>"        . $st_id . "</td>";
              $report .= "<td>"        . $st_libelle . "</td>";
              $report .= "<td>"        . $st_groupe . "</td>";
              $report .= "<td>"        . $st_description . "</td>";
              $report .= "</tr>";

              /** recherche du groupe de formation */
              $groupe = $this->getFormationGroupeService()->getFormationGroupeByLibelle($st_groupe);
              if ($groupe === null) {
                  $groupe = new FormationGroupe();
                  $groupe->setLibelle($st_groupe);
                  $groupe->setSource($this->sourceLagaf);
                  $this->getFormationGroupeService()->create($groupe);
                  $groupes[] = $groupe;
              }

              $formation = $this->getFormationService()->getFormationBySource($this->sourceLagaf, $st_id);
              if ($formation === null) {
                  $formation = new Formation();
                  $formation->setLibelle($st_libelle);
                  $formation->setGroupe($groupe);
                  $formation->setDescription($st_description);
                  $formation->setSource($this->sourceLagaf);
                  $formation->setIdSource($st_id);
                  $this->getFormationService()->create($formation);
                  $formations[] = $formation;
              }
        }
        $report .= "</tbody>";
        $report .= "</table>";

        return new ViewModel([
            'report' => $report,
            'formations' => $formations,
            'groupes' => $groupes,
            'themes' => $themes,
        ]);
    }

    public function sessionAction()
    {
        $report = "";
        $instances = [];
        $problemes = [];

        $file_path = "/tmp/TSession.csv";
        $handle = fopen($file_path, "r");
        $array = [];
        while ($content = fgetcsv ( $handle, 0, ";" , '"')) {
            $array[] = $content;
        }

        $nbLine = count($array);
        $report .= "Nombre de ligne dans le fichier CSV : " . $nbLine . "\n";

        $position_action_id     = array_search('N4Action', $array[0]);
        $position_session_id    = array_search('NSession', $array[0]);
        $position_lieu          = array_search('LieuConvoc', $array[0]);
        $position_responsable   = array_search('RespPédag', $array[0]);


        $report .= "<table class='table table-condensed'>";
        $report .= "<thead><tr><th>Action Id</th><th>Session Id</th><th>Lieu</th><th>Responsable</th></tr></thead>";
        $report .= "<tbody>";
        for($position = 1 ; $position < $nbLine; $position++) {
            $data = $array[$position];
            $st_action_id = $data[$position_action_id];
            $st_session_id = $data[$position_session_id];
            $st_lieu = (trim($data[$position_lieu]) !== '')?trim($data[$position_lieu]):null ;
            $st_responsable = (trim($data[$position_responsable]) !== '')?trim($data[$position_responsable]):null;

            $report .= "<tr>";
            $report .= "<td>"        . $st_action_id . "</td>";
            $report .= "<td>"        . $st_session_id . "</td>";
            $report .= "<td>"        . $st_lieu . "</td>";
            $report .= "<td>"        . $st_responsable . "</td>";
            $report .= "</tr>";

            /** recherche du groupe de formation */
            $formation = $this->getFormationService()->getFormationBySource($this->sourceLagaf, $st_action_id);
            if ($formation !== null) {
                $st_id_source = $st_action_id . "-" . $st_session_id;
                $instance = $this->getFormationInstanceService()->getFormationInstanceBySource($this->sourceLagaf, $st_id_source);
                if ($instance === null) {
                    $instance = new FormationInstance();
                    $instance->setFormation($formation);
                    $instance->setComplement($st_responsable);
                    $instance->setLieu($st_lieu);
                    $instance->setSource($this->sourceLagaf);
                    $instance->setIdSource($st_id_source);
                    $instance->setNbPlacePrincipale(0);
                    $instance->setNbPlaceComplementaire(0);
                    $this->getFormationInstanceService()->create($instance);
                    $instances[] = $instance;
                }
            }
            else {
                $problemes[] = ['raison' => "Pas de formation de trouver pour l'instance", 'data' => $data];
            }
        }
        $report .= "</tbody>";
        $report .= "</table>";

        return new ViewModel([
            'report' => $report,
            'instances' => $instances,
            'problemes' => $problemes,
        ]);
    }

    public function seanceAction()
    {
        $report = "";
        $instances = [];
        $problemes = [];

        $file_path = "/tmp/EMC_JOURNEE.csv";
        $handle = fopen($file_path, "r");
        $array = [];
        while ($content = fgetcsv ( $handle, 0, "," , '"')) {
            $array[] = $content;
        }

        $nbLine = count($array);
        $report .= "Nombre de ligne dans le fichier CSV : " . $nbLine . "\n";

        $position_action_id     = array_search('N6Action', $array[0]);
        $position_session_id    = array_search('N4Session', $array[0]);
        $position_seance_id     = array_search('N2Séance', $array[0]);
        $position_plage_id      = array_search('NPlage', $array[0]);
        $position_date          = array_search('DateSéance', $array[0]);
        $position_debut         = array_search('HDébut', $array[0]);
        $position_fin           = array_search('HFin', $array[0]);
        $position_lieu_1        = array_search('Intitulé', $array[0]);
        $position_lieu_2        = array_search('Adresse1L', $array[0]);
        $position_lieu_3        = array_search('Adresse2L', $array[0]);
        $position_lieu_4        = array_search('CPL', $array[0]);
        $position_lieu_5        = array_search('VilleL', $array[0]);
        $position_responsable   = array_search('Responsable', $array[0]);

        /** recherche du groupe de formation */
        $instances_tmp = $this->getFormationInstanceService()->getFormationsInstances();
        $instances = [];
        foreach ($instances_tmp as $instance) {
            if ($instance->getSource() === $this->sourceLagaf) {
                $instances[$instance->getIdSource()] = $instance;
            }
        }

        $report .= "<table class='table table-condensed'>";
        $report .= "<thead><tr><th>Action Id</th><th>Session Id</th><th>Seance Id</th><th>Date</th><th>Debut</th><th>Fin</th><th>Lieu</th><th>Responsable</th></tr></thead>";
        $report .= "<tbody>";
        for($position = 1 ; $position < $nbLine; $position++) {
            $data = $array[$position];
            $st_action_id = $data[$position_action_id];
            $st_session_id = $data[$position_session_id];
            $st_seance_id = $data[$position_seance_id];
            $st_plage_id = $data[$position_plage_id];
            $st_date = $data[$position_date];
            $st_debut = $data[$position_debut];
            $st_fin = $data[$position_fin];
            $st_lieu = implode("|", [$data[$position_lieu_1],$data[$position_lieu_2],$data[$position_lieu_3],$data[$position_lieu_4],$data[$position_lieu_5]]);
            $st_responsable = (trim($data[$position_responsable]) !== '')?trim($data[$position_responsable]):null;

            $report .= "<tr>";
            $report .= "<td>"        . $st_action_id . "</td>";
            $report .= "<td>"        . $st_session_id . "</td>";
            $report .= "<td>"        . $st_seance_id . "-" . $st_plage_id . "</td>";
            $report .= "<td>"        . $st_date . "</td>";
            $report .= "<td>"        . $st_debut . "</td>";
            $report .= "<td>"        . $st_fin . "</td>";
            $report .= "<td>"        . $st_lieu . "</td>";
            $report .= "<td>"        . $st_responsable . "</td>";
            $report .= "</tr>";

            $instance = isset($instances[$st_action_id . "-" . $st_session_id])?$instances[$st_action_id . "-" . $st_session_id]:null;
            if ($instance !== null) {
                $st_id_source = $st_seance_id . "-" . $st_plage_id;
                $journee = $this->getSeanceService()->getSeanceBySource($this->sourceLagaf, $st_id_source);
                if ($journee === null) {
                    $journee = new Seance();
                    $journee->setInstance($instance);
                    $journee->setJour(DateTime::createFromFormat('d/m/Y', $st_date));
                    $journee->setDebut($st_debut);
                    $journee->setFin($st_fin);
                    $journee->setLieu($st_lieu);
                    $journee->setRemarque($st_responsable);
                    $journee->setSource($this->sourceLagaf);
                    $journee->setIdSource($st_id_source);
                    $this->getSeanceService()->create($journee);
                    $instances[] = $journee;
                }
            }
            else {
                $problemes[] = ['raison' => "Pas d'instance de trouver pour l'instance", 'data' => $data];
            }
        }
        $report .= "</tbody>";
        $report .= "</table>";

        return new ViewModel([
            'report' => $report,
            'instances' => $instances,
            'problemes' => $problemes,
        ]);
    }

    public function stagiaireAction()
    {
        $report = "";
        $stagiaires = [];
        $problemes = [];

        $file_path = "/tmp/TStagiaire.csv";
        $handle = fopen($file_path, "r");
        $array = [];
        while ($content = fgetcsv ( $handle, 0, ";" , '"')) {
            $array[] = $content;
        }

        $nbLine = count($array);
        $report .= "Nombre de ligne dans le fichier CSV : " . $nbLine . "\n";

        $position_stagiaire_id     = array_search('NStagiaire', $array[0]);
        $position_harp_id          = array_search('NumStagiaire', $array[0]);
        $position_nom              = array_search('PatronymeS', $array[0]);
        $position_prenom           = array_search('PrénomS', $array[0]);
        $position_annee            = array_search('AnnéeNaiss', $array[0]);


        $report .= "<table class='table table-condensed'>";
        $report .= "<thead><tr><th>Stagiaire Id</th><th>Nom</th><th>Prenom</th><th>Année</th><th>harp</th><th>octopus</th></tr></thead>";
        $report .= "<tbody>";
        for($position = 1 ; $position < $nbLine; $position++) {
            $data = $array[$position];
            $st_stagiaire_id = $data[$position_stagiaire_id];
            $st_harp_id = (trim($data[$position_harp_id]) !== '')?trim($data[$position_harp_id]):null ;
            $st_nom = (trim($data[$position_nom]) !== '')?trim($data[$position_nom]):null ;
            $st_prenom = (trim($data[$position_prenom]) !== '')?trim($data[$position_prenom]):null ;
            $st_annee = (trim($data[$position_annee]) !== '')?trim($data[$position_annee]):null ;

            $stagiaire = new LAGAFStagiaire();
            $stagiaire->setNStagiaire($st_stagiaire_id);
            $stagiaire->setNom($st_nom);
            $stagiaire->setPrenom($st_prenom);
            $stagiaire->setAnnee($st_annee);
            $stagiaire->setHarpId($st_harp_id);

            /** @var Agent $agent */
            $agent = null;
            if ($st_harp_id === null) {
                $agent = $this->getStagiaireService()->getAgentService()->getAgentByIdentification($st_prenom, $st_nom, $st_annee);
                if ($agent !== null) {
                        //$stagiaire->setHarpId($agent->getHarpId());
                        $stagiaire->setOctopusId($agent->getId());
                }
            } else {
                $agent = $this->getStagiaireService()->getAgentService()->getAgentByHarp($st_harp_id);
                if ($agent !== null) $stagiaire->setOctopusId($agent->getId());
            }
            $this->getStagiaireService()->create($stagiaire);
            $stagiaires[] = $stagiaire;

            $report .= "<tr>";
            $report .= "<td>"        . $st_stagiaire_id . "</td>";
            $report .= "<td>"        . $st_nom . "</td>";
            $report .= "<td>"        . $st_prenom . "</td>";
            $report .= "<td>"        . $st_annee . "</td>";
            $report .= "<td>"        . $stagiaire->getHarpId() . "</td>";
            $report .= "<td>"        . $stagiaire->getOctopusId() . "</td>";
            $report .= "</tr>";

        }
        $report .= "</tbody>";
        $report .= "</table>";

        return new ViewModel([
            'report' => $report,
            'instances' => $stagiaires,
            'problemes' => $problemes,
        ]);
    }

    public function inscriptionAction()
    {
        $report = "";
        $inscriptions = [];
        $problemes = [];

        $file_path = "/tmp/TInscription.csv";
        $handle = fopen($file_path, "r");
        $array = [];
        while ($content = fgetcsv($handle, 0, ";", '"')) {
            $array[] = $content;
        }

        $nbLine = count($array);
        $report .= "Nombre de ligne dans le fichier CSV : " . $nbLine . "\n";

        $position_stagiaire_id     = array_search('N3Stagiaire', $array[0]);
        $position_action_id        = array_search('N3Action', $array[0]);
        $position_session_id       = array_search('N2Session', $array[0]);
        $position_FRepas           = array_search('FRepas', $array[0]);
        $position_FHebergement     = array_search('FHebergement', $array[0]);
        $position_FTransport       = array_search('FTransport', $array[0]);

        $instances_tmp = $this->getFormationInstanceService()->getFormationsInstances();
        $instances = [];
        foreach ($instances_tmp as $instance) {
            if ($instance->getSource() === $this->sourceLagaf) {
                $instances[$instance->getIdSource()] = $instance;
            }
        }
        $agents = [];
        $agents_tmp = $this->getStagiaireService()->getAgentService()->getAgents();
        foreach ($agents_tmp as $agent) $agents[$agent->getId()] = $agent;
        $stagiaires_tmp = $this->getStagiaireService()->getStagiaires();
        $stagiaires = [];
        foreach ($stagiaires_tmp as $stagiaire) {
            $stagiaires[$stagiaire->getNStagiaire()] = $stagiaire;
        }
        $validee = $this->getEtatService()->getEtatByCode('VALIDATION_INSCRIPTION');

        $report .= "<table class='table table-condensed'>";
        $report .= "<thead><tr><th>Stagiaire </th><th>Session</th><th>FRepas</th><th>FTransport</th><th>FHebergement</th></tr></thead>";
        $report .= "<tbody>";

        for($position = 1 ; $position < $nbLine; $position++) {

            $data = $array[$position];
            $st_nstagiaire = $data[$position_stagiaire_id];
            $st_instance = $data[$position_action_id] . "-" . $data[$position_session_id];

            $instance = (isset($instances[$st_instance]))?$instances[$st_instance]:null;
            $stagiaire = (isset($stagiaires[$st_nstagiaire]))?$stagiaires[$st_nstagiaire]:null;
            $agent = ($stagiaire !== null AND isset($agents[$stagiaire->getOctopusId()]))?$agents[$stagiaire->getOctopusId()]:null;

            if ($agent !== null AND $instance !== null) {
                $inscription = new FormationInstanceInscrit();
                $inscription->setInstance($instance);
                $inscription->setAgent($agent);
                $inscription->setListe("principale");
                $inscription->setSource($this->sourceLagaf);
                $inscription->setEtat($validee);
                $inscription->setIdSource($st_instance . "-" . $st_nstagiaire);
                $this->getFormationInstanceInscritService()->create($inscription);
                $inscriptions[] = $inscription;

                $st_frepas = trim($data[$position_FRepas]);
                $st_ftransport = trim($data[$position_FTransport]);
                $st_fhebergement = trim($data[$position_FHebergement]);

                if ($st_frepas !== "" OR $st_ftransport !== "" OR $st_fhebergement !== "") {
                    $frais = new FormationInstanceFrais();
                    if ($st_frepas !== "") $frais->setFraisRepas($st_frepas);
                    if ($st_ftransport !== "") $frais->setFraisTransport($st_ftransport);
                    if ($st_fhebergement !== "") $frais->setFraisHebergement($st_fhebergement);
                    $frais->setInscrit($inscription);
                    $frais->setSource($this->sourceLagaf);
                    $frais->setIdSource($st_instance . "-" . $st_nstagiaire);
                    $this->getFormationInstanceFraisService()->create($frais);
                }
            } else {
                $problemes[] = $data;
            }
        }

        $report .= "</tbody></table>";


        return new ViewModel([
            'report' => $report,
            'instances' => $inscriptions,
            'problemes' => $problemes,
        ]);
    }

    public function presenceAction()
    {
        $report = "";
        $presences = [];
        $problemes = [];

        $file_path = "/tmp/TPrésence.csv";
        $handle = fopen($file_path, "r");
        $array = [];
        while ($content = fgetcsv($handle, 0, ";", '"')) {
            $array[] = $content;
        }

        $nbLine = count($array);
        $report .= "Nombre de ligne dans le fichier CSV : " . $nbLine . "\n";

        $position_stagiaire_id     = array_search('NStagiaire', $array[0]);
        $position_action_id        = array_search('NAction', $array[0]);
        $position_session_id       = array_search('NSession', $array[0]);
        $position_seance_id        = array_search('NSéance', $array[0]);
        $position_plage_id         = array_search('NPlage', $array[0]);
        $position_presence         = array_search('Présence', $array[0]);


        $journees_tmp = $this->getSeanceService()->getSeances();
        $journees = [];
        foreach ($journees_tmp as $journee) {
            if ($journee->getSource() === $this->sourceLagaf) {
                $journees[$journee->getIdSource()] = $journee;
            }
        }
        $inscrits_tmp = $this->getFormationInstanceInscritService()->getFormationsInstancesInscrits();
        $inscrits = [];
        foreach ($inscrits_tmp as $inscrit) {
            if ($inscrit->getSource() === $this->sourceLagaf) {
                $inscrits[$inscrit->getIdSource()] = $inscrit;
            }
        }

        $presences_tmp = $this->getPresenceService()->getPresences();
        $olds = [];
        foreach ($presences_tmp as $presence) {
            if ($presence->getSource() === $this->sourceLagaf) {
                $olds[$presence->getIdSource()] = $presence;
            }
        }

        $report .= "<table class='table table-condensed'>";
        $report .= "<thead><tr><th>Stagiaire </th><th>Session</th><th>FRepas</th><th>FTransport</th><th>FHebergement</th></tr></thead>";
        $report .= "<tbody>";

        for($position = 1 ; $position < $nbLine; $position++) {

            $data = $array[$position];
            $st_journee = $data[$position_seance_id] . "-" . $data[$position_plage_id];
            $st_inscrit = $data[$position_action_id] . "-" . $data[$position_session_id] . "-" . $data[$position_stagiaire_id];

            $journee = (isset($journees[$st_journee]))?$journees[$st_journee]:null;
            $inscrit = (isset($inscrits[$st_inscrit]))?$inscrits[$st_inscrit]:null;

            if ($journee !== null AND $inscrit !== null) {
                if (!isset($olds[$st_journee . "-" . $st_inscrit])) {
                    $presence = new Presence();
                    $presence->setJournee($journee);
                    $presence->setInscrit($inscrit);
                    $presence->setPresent($data[$position_presence] === "1");
                    $presence->setSource($this->sourceLagaf);
                    $presence->setIdSource($st_journee . "-" . $st_inscrit);
                    $this->getPresenceService()->create($presence);
                    $presences[] = $presence;
                }
            } else {
                $problemes[] = $data;
            }
        }

        $report .= "</tbody></table>";


        return new ViewModel([
            'report' => $report,
            'instances' => $presences,
            'problemes' => $problemes,
        ]);
    }

    public function elementAction() {

        $nbElement = 0;
        $formations = $this->getFormationService()->getFormations();
        foreach ($formations as $formation) {
            if ($formation->getSource() === $this->sourceLagaf) {
                foreach ($formation->getInstances() as $instance) {
                    foreach ($instance->getInscrits() as $inscrit) {
                        $agent = $inscrit->getAgent();
                        if (!$agent->hasFormation($formation)) {
                            $formationElement = new FormationElement();
                            $formationElement->setFormation($formation);
                            $formationElement->setCommentaire($instance->getDebut() . " - Importée depuis lagaf");
                            $this->getHasFormationCollectionService()->addFormation($agent, $formationElement);
                            $nbElement++;
                        }
                    }
                }
            }
        }
        return new ViewModel([
            "report" => $nbElement,
            "instances" => [],
            "problemes" => [],
        ]);
    }
}