<?php

namespace Formation\Controller;

use DateTime;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationGroupe;
use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\FormationInstanceJournee;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\FormationInstance\FormationInstanceServiceAwareTrait;
use Formation\Service\FormationInstanceJournee\FormationInstanceJourneeServiceAwareTrait;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ImportationLagafController extends AbstractActionController {
    use FormationServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use FormationInstanceServiceAwareTrait;
    use FormationInstanceJourneeServiceAwareTrait;

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
                  $groupe->setSource("LAGAF");
                  $this->getFormationGroupeService()->create($groupe);
                  $groupes[] = $groupe;
              }

              $formation = $this->getFormationService()->getFormationBySource("LAGAF", $st_id);
              if ($formation === null) {
                  $formation = new Formation();
                  $formation->setLibelle($st_libelle);
                  $formation->setGroupe($groupe);
                  $formation->setDescription($st_description);
                  $formation->setSource("LAGAF");
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
            $formation = $this->getFormationService()->getFormationBySource('LAGAF', $st_action_id);
            if ($formation !== null) {
                $st_id_source = $st_action_id . "-" . $st_session_id;
                $instance = $this->getFormationInstanceService()->getFormationInstanceBySource('LAGAF', $st_id_source);
                if ($instance === null) {
                    $instance = new FormationInstance();
                    $instance->setFormation($formation);
                    $instance->setComplement($st_responsable);
                    $instance->setLieu($st_lieu);
                    $instance->setSource('LAGAF');
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

            /** recherche du groupe de formation */
            $instance = $this->getFormationInstanceService()->getFormationInstanceBySource('LAGAF', $st_action_id."-".$st_session_id);
            if ($instance !== null) {
                $st_id_source = $st_seance_id . "-" . $st_plage_id;
                $journee = $this->getFormationInstanceJourneeService()->getFormationInstanceJourneeBySource('LAGAF', $st_id_source);
                if ($journee === null) {
                    $journee = new FormationInstanceJournee();
                    $journee->setInstance($instance);
                    $journee->setJour(DateTime::createFromFormat('d/m/Y', $st_date));
                    $journee->setDebut($st_debut);
                    $journee->setFin($st_fin);
                    $journee->setLieu($st_lieu);
                    $journee->setRemarque($st_responsable);
                    $journee->setSource("LAGAF");
                    $journee->setIdSource($st_id_source);
                    $this->getFormationInstanceJourneeService()->create($journee);
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
}