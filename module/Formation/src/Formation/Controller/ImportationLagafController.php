<?php

namespace Formation\Controller;

use Application\Entity\Db\Agent;
use DateTime;
use Formation\Entity\Db\Formation;
use Formation\Entity\Db\FormationElement;
use Formation\Entity\Db\FormationGroupe;
use Formation\Entity\Db\Session;
use Formation\Entity\Db\Inscription;
use Formation\Entity\Db\InscriptionFrais;
use Formation\Entity\Db\LAGAFStagiaire;
use Formation\Entity\Db\Presence;
use Formation\Entity\Db\Seance;
use Formation\Provider\Etat\InscriptionEtats;
use Formation\Provider\Etat\SessionEtats;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Formation\Service\FormationGroupe\FormationGroupeServiceAwareTrait;
use Formation\Service\HasFormationCollection\HasFormationCollectionServiceAwareTrait;
use Formation\Service\Inscription\InscriptionServiceAwareTrait;
use Formation\Service\InscriptionFrais\InscriptionFraisServiceAwareTrait;
use Formation\Service\Presence\PresenceServiceAwareTrait;
use Formation\Service\Seance\SeanceServiceAwareTrait;
use Formation\Service\Session\SessionServiceAwareTrait;
use Formation\Service\Stagiaire\StagiaireServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use UnicaenApp\Exception\RuntimeException;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class ImportationLagafController extends AbstractActionController
{
    use EtatInstanceServiceAwareTrait;
    use FormationServiceAwareTrait;
    use FormationGroupeServiceAwareTrait;
    use SeanceServiceAwareTrait;
    use SessionServiceAwareTrait;
    use InscriptionServiceAwareTrait;
    use InscriptionFraisServiceAwareTrait;
    use PresenceServiceAwareTrait;
    use StagiaireServiceAwareTrait;
    use UserServiceAwareTrait;
    use HasFormationCollectionServiceAwareTrait;

    public string $sourceLagaf;

    public function indexAction(): ViewModel
    {
        return new ViewModel();
    }

    /** IMPORT SANS REMPLACEMENT **************************************************************************************/

    public function themeAction(): ViewModel
    {

        $report = "";
        $file_path = "upload/Source700_020922/Theme.csv";
        $handle = fopen($file_path, "r");
        $array = [];
        while ($content = fgetcsv($handle, 0)) {
            $array[] = $content;
        }

        $theme_index = array_search('Thème', $array[0]);
        $id_index = array_search('TypeForm1', $array[0]);
        for ($position = 1; $position < count($array); $position++) {
            $data = $array[$position];
            $libelle = $data[$theme_index];
            $id = $data[$id_index];

            $in = $this->getFormationGroupeService()->getFormationGroupeBySource($this->sourceLagaf, $id);

            if ($in === null) {
                $groupe = new FormationGroupe();
                $groupe->setLibelle($libelle);
                $groupe->setDescription("Thème de formation importé de LAGAF (ligne " . $position);
                $groupe->setSource($this->sourceLagaf);
                $groupe->setIdSource($id);
                $this->getFormationGroupeService()->create($groupe);
                $report .= "Ajout du thème " . $libelle . "(LAGAF - " . $id . ") <br/>";
            }
        }

        $vm = new ViewModel(
            [
                "import" => "LAGAF FormationGroupe",
                "report" => $report,
            ]
        );
        $vm->setTemplate('formation/importation-lagaf/import');
        return $vm;

    }

    public function actionAction(): ViewModel
    {
        $report = "";
        $formations = [];
        $groupes = [];
        $themes = [];

        $file_path = "upload/Source700_020922/Action.csv";
        $handle = fopen($file_path, "r");
        $array = [];
        while ($content = fgetcsv($handle, 0)) {
            $array[] = $content;
        }

        $nbLine = count($array);
        $report .= "Nombre de ligne dans le fichier CSV : " . $nbLine . "\n";

        $position_id = array_search('NAction', $array[0]);
        $position_libelle = array_search('Action', $array[0]);
        $position_groupe = array_search('Domaine', $array[0]);
        $position_description = array_search('Objectifs', $array[0]);


        $report .= "<table class='table table-condensed'>";
        $report .= "<thead><tr><th>Id</th><th>Libelle</th><th>Groupe</th><th>Description</th></tr></thead>";
        $report .= "<tbody>";
        for ($position = 1; $position < $nbLine; $position++) {
            $data = $array[$position];
            $st_id = $data[$position_id];
            $st_libelle = $data[$position_libelle];
            $st_groupe = $data[$position_groupe];
            $st_description = $data[$position_description];

            $report .= "<tr>";
            $report .= "<td>" . $st_id . "</td>";
            $report .= "<td>" . $st_libelle . "</td>";
            $report .= "<td>" . $st_groupe . "</td>";
            $report .= "<td>" . $st_description . "</td>";
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

    public function sessionAction(): ViewModel
    {
        $report = "";
        $instances = [];
        $problemes = [];

        $file_path = "upload/Source700_020922/Session.csv";
        $handle = fopen($file_path, "r");
        $array = [];
        while ($content = fgetcsv($handle, 0)) {
            $array[] = $content;
        }

        $nbLine = count($array);
        $report .= "Nombre de ligne dans le fichier CSV : " . $nbLine . "\n";

        $position_action_id = array_search('N4Action', $array[0]);
        $position_session_id = array_search('NSession', $array[0]);
        $position_lieu = array_search('LieuConvoc', $array[0]);
        $position_responsable = array_search('RespPédag', $array[0]);


        $report .= "<table class='table table-condensed'>";
        $report .= "<thead><tr><th>Action Id</th><th>Session Id</th><th>Lieu</th><th>Responsable</th></tr></thead>";
        $report .= "<tbody>";


        $lagaf = $this->sourceLagaf;

        $actions = $this->getFormationService()->getFormations();
        $actions = array_filter($actions, function (Formation $a) use ($lagaf) {
            return $a->getSource() === $lagaf;
        });
        $dictionnaireA = [];
        foreach ($actions as $action) $dictionnaireA[$action->getIdSource()] = $action;

        $sessions = $this->getSessionService()->getSessions();
        $sessions = array_filter($sessions, function (Session $a) use ($lagaf) {
            return $a->getSource() === $lagaf;
        });
        $dictionnaireS = [];
        foreach ($sessions as $session) $dictionnaireS[$session->getIdSource()] = $session;

        for ($position = 1; $position < $nbLine; $position++) {
            $data = $array[$position];
            $st_action_id = $data[$position_action_id];
            $st_session_id = $data[$position_session_id];
            $st_lieu = (trim($data[$position_lieu]) !== '') ? trim($data[$position_lieu]) : null;
            $st_responsable = (trim($data[$position_responsable]) !== '') ? trim($data[$position_responsable]) : null;

            $report .= "<tr>";
            $report .= "<td>" . $st_action_id . "</td>";
            $report .= "<td>" . $st_session_id . "</td>";
            $report .= "<td>" . $st_lieu . "</td>";
            $report .= "<td>" . $st_responsable . "</td>";
            $report .= "</tr>";

            $idOrig = $st_action_id . "-" . $st_session_id;
            if ($dictionnaireA[$st_action_id] !== null) {
                if (!isset($dictionnaireS[$idOrig])) {
                    $session = new Session();
                    $session->setFormation($dictionnaireA[$st_action_id]);
                    $session->setComplement($st_responsable);
                    $session->setLieu($st_lieu);
                    $session->setSource($this->sourceLagaf);
                    $session->setIdSource($idOrig);
                    $session->setNbPlacePrincipale(0);
                    $session->setNbPlaceComplementaire(0);
                    $session->setAutoInscription();
                    $this->getSessionService()->create($session);
                    $this->getEtatInstanceService()->setEtatActif($session, SessionEtats::ETAT_CLOTURE_INSTANCE);
                    $this->getSessionService()->update($session);

                    $instances[] = $session;
                }
            } else {
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

    public function seanceAction(): ViewModel
    {
        $report = "";
        $instances = [];
        $problemes = [];

        $file_path = "upload/Source700_020922/Seance.csv";
        $handle = fopen($file_path, "r");
        $arraySeance = [];
        while ($content = fgetcsv($handle, 0)) {
            $arraySeance[] = $content;
        }

        $file_path = "upload/Source700_020922/Plage.csv";
        $handle = fopen($file_path, "r");
        $arrayPlage = [];
        while ($content = fgetcsv($handle, 0)) {
            $arrayPlage[] = $content;
        }

        $nbLine = count($arraySeance);
        $report .= "Nombre de ligne dans le fichier CSV : " . $nbLine . "\n";

        $position_action_id = array_search('N6Action', $arraySeance[0]); //seance
        $position_session_id = array_search('N4Session', $arraySeance[0]); //seance
        $position_seance_id = array_search('NSéance', $arraySeance[0]); //plage <-> Nseance de seance
        $position_date = array_search('DateSéance', $arraySeance[0]); //seance

        $position_seance2_id = array_search('N2Séance', $arrayPlage[0]); //plage <-> Nseance de seance
        $position_plage_id = array_search('NPlage', $arrayPlage[0]); //plage
        $position_debut = array_search('HDébut', $arrayPlage[0]); //plage
        $position_fin = array_search('HFin', $arrayPlage[0]); //plage

        $lagaf = $this->sourceLagaf;

        $sessions = $this->getSessionService()->getSessions();
        $sessions = array_filter($sessions, function (Session $a) use ($lagaf) {
            return $a->getSource() === $lagaf;
        });
        $dictionnaireS = [];
        foreach ($sessions as $session) $dictionnaireS[$session->getIdSource()] = $session;

        $seances = $this->getSeanceService()->getSeances();
        $seances = array_filter($seances, function (Seance $a) use ($lagaf) {
            return $a->getSource() === $lagaf;
        });
        $dictionnaireP = [];
        foreach ($seances as $seance) $dictionnaireP[$seance->getIdSource()] = $seance;

        for ($position_seance = 1; $position_seance < count($arraySeance); $position_seance++) {
            $action_id = $arraySeance[$position_seance][$position_action_id];
            $session_id = $arraySeance[$position_seance][$position_session_id];
            $date = $arraySeance[$position_seance][$position_date];
            $seance_id = $arraySeance[$position_seance][$position_seance_id];


            if ($dictionnaireS[$action_id . "-" . $session_id]) {
                $plages = array_filter($arrayPlage, function ($a) use ($position_seance2_id, $seance_id) {
                    return $a[$position_seance2_id] == $seance_id;
                });
                foreach ($plages as $plage) {
                    $plage_id = $action_id . "-" . $session_id . "-" . $seance_id . "-" . $plage[$position_plage_id];

                    if (!isset($dictionnaireP[$plage_id])) {
                        $seance = new Seance();
                        $seance->setSource($this->sourceLagaf);
                        $seance->setIdSource($plage_id);
                        $aaa = DateTime::createFromFormat('m/d/y H:i:s', $date);
                        if (!$aaa instanceof DateTime) throw new RuntimeException("Pb de date : " . $date);
                        $seance->setJour($aaa);

                        $arrayDebut = null;
                        $arrayFin = null;
                        $debut = explode(" ", $plage[$position_debut]);
                        if (isset($debut[1])) $arrayDebut = explode(":", $debut[1]);
                        $fin = explode(" ", $plage[$position_fin]);
                        if (isset($fin[1])) $arrayFin = explode(":", $fin[1]);
                        if ($arrayDebut) $seance->setDebut($arrayDebut[0] . ":" . $arrayDebut[1]);
                        if ($arrayFin) $seance->setFin($arrayFin[0] . ":" . $arrayFin[1]);

                        $seance->setInstance($dictionnaireS[$action_id . "-" . $session_id]);
                        $seance->setType("SEANCE");
                        $seance->setOldLieu("Import LAGAF");
                        $this->getSeanceService()->create($seance);
                    }
                }
            } else {
                $problemes[] .= "Session " . $seance_id . " non trouvée";
            }
        }

        return new ViewModel([
            'report' => $report,
            'instances' => $instances,
            'problemes' => $problemes,
        ]);
    }

    public function stagiaireAction(): ViewModel
    {
        $report = "";
        $stagiaires = [];
        $problemes = [];

        $file_path = "upload/Source700_020922/Stagiaire.csv";
        $handle = fopen($file_path, "r");
        $array = [];
        while ($content = fgetcsv($handle, 0)) {
            $array[] = $content;
        }

        $nbLine = count($array);
        $report .= "Nombre de ligne dans le fichier CSV : " . $nbLine . "\n";

        $position_stagiaire_id = array_search('NStagiaire', $array[0]);
        $position_harp_id = array_search('NumStagiaire', $array[0]);
        $position_nom = array_search('PatronymeS', $array[0]);
        $position_prenom = array_search('PrénomS', $array[0]);
        $position_annee = array_search('AnnéeNaiss', $array[0]);


        $report .= "<table class='table table-condensed'>";
        $report .= "<thead><tr><th>Stagiaire Id</th><th>Nom</th><th>Prenom</th><th>Année</th><th>harp</th><th>octopus</th></tr></thead>";
        $report .= "<tbody>";
        for ($position = 1; $position < $nbLine; $position++) {
            $data = $array[$position];
            $st_stagiaire_id = $data[$position_stagiaire_id];
            $st_harp_id = (trim($data[$position_harp_id]) !== '') ? trim($data[$position_harp_id]) : null;
            $st_nom = (trim($data[$position_nom]) !== '') ? trim($data[$position_nom]) : null;
            $st_prenom = (trim($data[$position_prenom]) !== '') ? trim($data[$position_prenom]) : null;
            $st_annee = (trim($data[$position_annee]) !== '') ? trim($data[$position_annee]) : null;

            $stagiaire = new LAGAFStagiaire();
            $stagiaire->setNStagiaire($st_stagiaire_id);
            $stagiaire->setNom($st_nom);
            $stagiaire->setPrenom($st_prenom);
            $stagiaire->setAnnee($st_annee);
            $stagiaire->setHarpId($st_harp_id);

            /** @var Agent $agent */
            $agent = null;
            $agent = $this->getStagiaireService()->getAgentService()->getAgentByIdentification($st_prenom, $st_nom);
            if ($agent) $stagiaire->setOctopusId((string)$agent->getId());
            $this->getStagiaireService()->create($stagiaire);
            $stagiaires[] = $stagiaire;

            $report .= "<tr>";
            $report .= "<td>" . $st_stagiaire_id . "</td>";
            $report .= "<td>" . $st_nom . "</td>";
            $report .= "<td>" . $st_prenom . "</td>";
            $report .= "<td>" . $st_annee . "</td>";
            $report .= "<td>" . $stagiaire->getHarpId() . "</td>";
            $report .= "<td>" . $stagiaire->getOctopusId() . "</td>";
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

    public function inscriptionAction(): ViewModel
    {
        $id = $this->params()->fromRoute('id');
        $emc2 = $this->getUserService()->getRepo()->find(0);
        $report = "";
        $inscriptions = [];
        $problemes = [];

        $file_path = "upload/Source700_020922/Inscription.csv";
        $handle = fopen($file_path, "r");
        $array = [];
        while ($content = fgetcsv($handle, 0)) {
            $array[] = $content;
        }

        $nbLine = count($array);
        $report .= "Nombre de ligne dans le fichier CSV : " . $nbLine . "\n";

        $position_stagiaire_id = array_search('N3Stagiaire', $array[0]);
        $position_action_id = array_search('N3Action', $array[0]);
        $position_session_id = array_search('N2Session', $array[0]);
        $position_FRepas = array_search('FRepas', $array[0]);
        $position_FHebergement = array_search('FHebergement', $array[0]);
        $position_FTransport = array_search('FTransport', $array[0]);

        $instances_tmp = $this->getSessionService()->getSessions();
        $instances = [];
        foreach ($instances_tmp as $instance) {
            if ($instance->getSource() === $this->sourceLagaf) {
                $instances[$instance->getIdSource()] = $instance;
            }
        }

        $stagiaires = [];
        $agents = [];

        if ($id) {
            $stagiaire = $this->getStagiaireService()->getStagiaire($id);
            $stagiaires[$id] = $stagiaire;
            $agents[$stagiaire->getOctopusId()] = $this->getStagiaireService()->getAgentService()->getAgent($stagiaire->getOctopusId());
        } else {
            $stagiaires_tmp = $this->getStagiaireService()->getStagiaires();
            foreach ($stagiaires_tmp as $stagiaire) {
                $stagiaires[$stagiaire->getNStagiaire()] = $stagiaire;
            }
            $agents_tmp = $this->getStagiaireService()->getAgentService()->getAgents();
            foreach ($agents_tmp as $agent) $agents[$agent->getId()] = $agent;
        }

        $lagaf = $this->sourceLagaf;

        if ($id) {
            $inscrits_tmp = $this->getInscriptionService()->getInscriptionsByAgent(current($agents));
        } else {
            $inscrits_tmp = $this->getInscriptionService()->getInscriptions();
        }
        $inscrits_tmp = array_filter($inscrits_tmp, function (Inscription $a) use ($lagaf) {
            return $a->getSource() === $lagaf;
        });
        $inscrits = [];
        foreach ($inscrits_tmp as $inscrit) $inscrits[$inscrit->getId()] = $inscrit;


        $report .= "<table class='table table-condensed'>";
        $report .= "<thead><tr><th>Stagiaire </th><th>Session</th><th>FRepas</th><th>FTransport</th><th>FHebergement</th></tr></thead>";
        $report .= "<tbody>";


        for ($position = 1; $position < $nbLine; $position++) {

            $data = $array[$position];
            $st_nstagiaire = $data[$position_stagiaire_id];
            $st_instance = $data[$position_action_id] . "-" . $data[$position_session_id];

            $instance = (isset($instances[$st_instance])) ? $instances[$st_instance] : null;
            $stagiaire = (isset($stagiaires[$st_nstagiaire])) ? $stagiaires[$st_nstagiaire] : null;

            $agent = ($stagiaire !== null and isset($agents[$stagiaire->getOctopusId()])) ? $agents[$stagiaire->getOctopusId()] : null;
            $st_iscrit_id = $st_instance . "-" . $st_nstagiaire;

            if ($id === null or $st_nstagiaire == $id) {

                if ($agent !== null and $instance !== null and !isset($inscrits[$st_iscrit_id])) {
                    $inscription = new Inscription();
                    $inscription->setSession($instance);
                    $inscription->setAgent($agent);
                    $inscription->setListe("principale");
                    $inscription->setSource($this->sourceLagaf);
                    $inscription->setIdSource($st_iscrit_id);
                    $inscription->setHistoCreation(new DateTime());
                    $inscription->setHistoModification(new DateTime());
                    $inscription->setHistoCreateur($emc2);
                    $inscription->setHistoModificateur($emc2);
                    $this->getInscriptionService()->create($inscription);
                    $this->getEtatInstanceService()->setEtatActif($inscription, InscriptionEtats::ETAT_VALIDER_DRH);
                    $this->getInscriptionService()->update($inscription);
                    $inscriptions[] = $inscription;

                    $st_frepas = trim($data[$position_FRepas]);
                    $st_ftransport = trim($data[$position_FTransport]);
                    $st_fhebergement = trim($data[$position_FHebergement]);

                    if ($st_frepas !== "" or $st_ftransport !== "" or $st_fhebergement !== "") {
                        $frais = new InscriptionFrais();
                        if ($st_frepas !== "") $frais->setFraisRepas($st_frepas);
                        if ($st_ftransport !== "") $frais->setFraisTransport($st_ftransport);
                        if ($st_fhebergement !== "") $frais->setFraisHebergement($st_fhebergement);
                        $frais->setInscrit($inscription);
                        $frais->setSource($this->sourceLagaf);
                        $frais->setIdSource($st_instance . "-" . $st_nstagiaire);
                        $this->getInscriptionFraisService()->create($frais);
                    }

                    if ($id !== null) {
                        $report .= "<tr>";
                        $report .= "<td>" . $agent->getDenomination() . "</td>";
                        $report .= "<td>" . $instance->getFormation()->getLibelle() . "</td>";
                        $report .= "</tr>";
                    }
                } else {
                    $problemes[] = $data;
                }
            }
        }
        $report .= "</tbody></table>";


        return new ViewModel([
            'report' => $report,
            'instances' => $inscriptions,
            'problemes' => $problemes,
        ]);
    }

    public function presenceAction(): ViewModel
    {

        $id = $this->params()->fromRoute('id');

        $report = "";
        $presences = [];
        $problemes = [];

        $file_path = "upload/Source700_020922/Presence.csv";
        $handle = fopen($file_path, "r");
        $array = [];
        while ($content = fgetcsv($handle, 0)) {
            $array[] = $content;
        }

        $nbLine = count($array);
        $report .= "Nombre de ligne dans le fichier CSV : " . $nbLine . "\n";

        $position_stagiaire_id = array_search('NStagiaire', $array[0]);
        $position_action_id = array_search('NAction', $array[0]);
        $position_session_id = array_search('NSession', $array[0]);
        $position_seance_id = array_search('NSéance', $array[0]);
        $position_plage_id = array_search('NPlage', $array[0]);
        $position_presence = array_search('Présence', $array[0]);

        $journees_tmp = $this->getSeanceService()->getSeances();
        $journees = [];
        foreach ($journees_tmp as $journee) {
            if ($journee->getSource() === $this->sourceLagaf) {
                $journees[$journee->getIdSource()] = $journee;
            }
        }
        $inscrits_tmp = $this->getInscriptionService()->getInscriptions();
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

        for ($position = 1; $position < $nbLine; $position++) {

            $data = $array[$position];


            if ($id === null or $data[$position_stagiaire_id] == $id) {

                $st_journee = $data[$position_action_id] . "-" . $data[$position_session_id] . "-" . $data[$position_seance_id] . "-" . $data[$position_plage_id];
                $st_inscrit = $data[$position_action_id] . "-" . $data[$position_session_id] . "-" . $data[$position_stagiaire_id];
                $journee = (isset($journees[$st_journee])) ? $journees[$st_journee] : null;
                $inscrit = (isset($inscrits[$st_inscrit])) ? $inscrits[$st_inscrit] : null;

                if ($journee !== null and $inscrit !== null) {
                    if (!isset($olds[$st_journee . "-" . $st_inscrit])) {
                        $presence = new Presence();
                        $presence->setJournee($journee);
                        $presence->setInscription($inscrit);
                        $presence->setSource($this->sourceLagaf);
                        $presence->setIdSource($st_journee . "-" . $st_inscrit);
                        $presence->setPresenceType("LAGAF");
                        if ($data[$position_presence] === "1") {
                            $presence->setStatut(Presence::PRESENCE_PRESENCE);
                        } else {
                            $presence->setStatut(Presence::PRESENCE_ABSENCE_JUSTIFIEE);
                        }
                        $this->getPresenceService()->create($presence);

                        $presences[] = $presence;
                    }
                } else {
                    $problemes[] = $data;
                }
            }
        }

        $report .= "</tbody></table>";


        return new ViewModel([
            'report' => $report,
            'instances' => $presences,
            'problemes' => $problemes,
        ]);
    }

    public function elementAction(): ViewModel
    {

        $nbElement = 0;
        $formations = $this->getFormationService()->getFormations();
        foreach ($formations as $formation) {
            if ($formation->getSource() === $this->sourceLagaf) {
                foreach ($formation->getInstances() as $instance) {
                    foreach ($instance->getInscriptions() as $inscrit) {
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