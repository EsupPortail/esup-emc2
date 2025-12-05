<?php

namespace FicheMetier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Carriere\Entity\Db\NiveauEnveloppe;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeFormAwareTrait;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeServiceAwareTrait;
use DateTime;
use Element\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Element\Form\SelectionCompetence\SelectionCompetenceFormAwareTrait;
use Element\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Exception;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Entity\Db\MissionActivite;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\MissionActivite\MissionActiviteServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use Fichier\Service\Fichier\FichierServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Metier\Form\SelectionnerFamilleProfessionnelle\SelectionnerFamilleProfessionnelleFormAwareTrait;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class MissionPrincipaleController extends AbstractActionController
{
    use ApplicationElementServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use FichierServiceAwareTrait;
    use MissionActiviteServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use NiveauEnveloppeServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use ReferentielServiceAwareTrait;

    use ModifierLibelleFormAwareTrait;
    use NiveauEnveloppeFormAwareTrait;
    use SelectionApplicationFormAwareTrait;
    use SelectionCompetenceFormAwareTrait;
    use SelectionnerFamilleProfessionnelleFormAwareTrait;



    public function indexAction(): ViewModel
    {
        $params = $this->params()->fromQuery();
        $missions = $this->getMissionPrincipaleService()->getMissionsPrincipalesWithFiltre($params);

        return new ViewModel([
            'missions' => $missions,
            'referentiels' => $this->getReferentielService()->getReferentiels(),
            'familles' => $this->getFamilleProfessionnelleService()->getFamillesProfessionnelles(),
            'params' => $params,
        ]);
    }

    /** CRUD ****************************************************************************************/

    public function afficherAction(): ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);

        $vm = new ViewModel([
            'title' => "Affichage de la mission principale",
            'modification' => false,
            'mission' => $mission,
            'fichesmetiers' => $mission->getListeFicheMetier(),
            'fichespostes' => $mission->getListeFichePoste(),
            'codeFonction' => $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::CODE_FONCTION)
        ]);
        return $vm;
    }

    public function ajouterAction(): Response
    {
        $mission = new Mission();
        $referentiel = $this->getReferentielService()->getReferentielByLibelleCourt('EMC2');
        $mission->setReferentiel($referentiel);
        $this->getMissionPrincipaleService()->create($mission);
        $mission->setReference($mission->getId());
        $this->getMissionPrincipaleService()->update($mission);

        return $this->redirect()->toRoute('mission-principale/modifier', ['mission-principale' => $mission->getId()], [], true);
    }

    public function modifierAction(): ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);
        $retour = $this->params()->fromQuery('retour') ?? null;

        $ficheMetier = null;
        if ($retour) {
            $split = explode("/", $retour);
            $ficheId = end($split);
            if ($ficheId) {
                $ficheMetier = $this->getFicheMetierService()->getFicheMetier($ficheId);
            }
        }


        return new ViewModel([
            'mission' => $mission,
            'modification' => true,
            'ficheMetier' => $ficheMetier ?? null,
            'fichesmetiers' => $mission->getListeFicheMetier(),
            'fichespostes' => $mission->getListeFichePoste(),
            'retour' => $retour,
        ]);
    }

    public function historiserAction(): Response
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);
        $this->getMissionPrincipaleService()->historise($mission);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('mission-principale');
    }

    public function restaurerAction(): Response
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);
        $this->getMissionPrincipaleService()->restore($mission);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('mission-principale');
    }

    public function supprimerAction(): ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            if ($data["reponse"] === "oui") {
                $this->getMissionPrincipaleService()->delete($mission);
            }
            exit();
        }

        $vm = new ViewModel();
        if ($mission !== null) {
            $nbFicheMetier = count($mission->getListeFicheMetier());
            $nbFichePoste = 0;
            $warning = "<span class='icon icon-attention'></span> Attention cette mission principale est encore associée à " . $nbFicheMetier . " fiches métiers et à " . $nbFichePoste . " fiches de poste.";
            $vm->setTemplate('default/confirmation');
            $vm->setVariables([
                'title' => "Suppression de la mission [" . $mission->getLibelle() . "]",
                'text' => "La suppression est définitive êtes-vous sûr&middot;e de vouloir continuer ?",
                'warning' => (($nbFicheMetier + $nbFichePoste) > 0) ? $warning : null,
                'action' => $this->url()->fromRoute('mission-principale/supprimer', ["mission-principale" => $mission->getId()], [], true),
            ]);
        }
        return $vm;
    }

    /** MODIFICATION DES ELEMENTS **************************************************************************/

    public function modifierLibelleAction(): ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-principale/modifier-libelle', ['mission-principale' => $mission->getId()], [], true));
        $form->bind($mission);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionPrincipaleService()->update($mission);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification du libellé de la mission principale",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function gererFamillesProfessionnellesAction(): ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);

        $form = $this->getSelectionnerFamilleProfessionnelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-principale/gerer-familles-professionnelles', ['mission' => $mission->getId()], [], true));
        $form->bind($mission);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionPrincipaleService()->update($mission);
            }
        }

        $vm = new ViewModel([
            'title' => "Sélectionner les familles professionnelles associé à la mission principale",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function gererNiveauAction(): ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);

        $niveaux = $mission->getNiveau();
        if ($niveaux === null) {
            $niveaux = new NiveauEnveloppe();
        }

        $form = $this->getNiveauEnveloppeForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-principale/gerer-niveau', ['mission-principale' => $mission->getId()], [], true));
        $form->bind($niveaux);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                if ($niveaux->getHistoCreation()) {
                    $this->getNiveauEnveloppeService()->update($niveaux);
                } else {
                    $this->getNiveauEnveloppeService()->create($niveaux);
                    $mission->setNiveau($niveaux);
                    $this->getMissionPrincipaleService()->update($mission);
                }
            }
        }

        $vm = new ViewModel([
            'title' => "Modifier le niveau associé à la mission",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function ajouterActiviteAction(): ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);

        $activite = new MissionActivite();
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-principale/ajouter-activite', ['mission-principale' => $mission->getId()], [], true));
        $form->bind($activite);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionPrincipaleService()->ajouterActivite($mission, $activite);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajouter une activité à la mission principale",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function modifierActiviteAction(): ViewModel
    {
        $activite = $this->getMissionActiviteService()->getRequestedActivite($this);
        $form = $this->getModifierLibelleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-principale/modifier-activite', ['mission-principale' => $activite->getId()], [], true));
        $form->bind($activite);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionActiviteService()->update($activite);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modifier le libellé de l'activité",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function supprimerActiviteAction(): Response
    {
        $activite = $this->getMissionActiviteService()->getRequestedActivite($this);
        $mission = $activite->getMission();
        $this->getMissionActiviteService()->delete($activite);

        $retour = $this->params()->fromQuery('retour');
        if ($retour) return $this->redirect()->toUrl($retour);
        return $this->redirect()->toRoute('mission-principale/modifier', ['mission-principale' => $mission->getId()], [], true);
    }

    public function gererApplicationsAction(): ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);
        $form = $this->getSelectionApplicationForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-principale/gerer-applications', ['mission-principale' => $mission->getId()], [], true));
        $form->bind($mission);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionPrincipaleService()->update($mission);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Gestion des applications associées à la mission",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    public function gererCompetencesAction(): ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);
        $form = $this->getSelectionCompetenceForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-principale/gerer-competences', ['mission-principale' => $mission->getId()], [], true));
        $form->bind($mission);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $this->getMissionPrincipaleService()->update($mission);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Gestion des compétences associées à la mission",
            'form' => $form,
        ]);
        $vm->setTemplate('default/default-form');
        return $vm;
    }

    /** FONCTIONS DE RECHERCHE ****************************************************************************************/

    public function rechercherAction(): JsonModel
    {
        if (($term = $this->params()->fromQuery('term'))) {
            $missions = $this->getMissionPrincipaleService()->findMissionsPrincipalesByExtendedTerm($term);
            $result = $this->getMissionPrincipaleService()->formatToJSON($missions);
            return new JsonModel($result);
        }
        exit;
    }

    /** IMPORTATIONS **************************************************************************************************/

    public function importerAction(): ViewModel
    {
        $separateur = '|';


        $request = $this->getRequest();

        $data = null;
        $missions = [];
        $error = [];
        $warning = [];
        $info = [];

        $filename = null;
        $filepath = null;

        if ($request->isPost()) {
            $data = $request->getPost();
            $files = $request->getFiles()->toArray();
            $file = !empty($files) ? current($files) : null;

            $filename = $data['filename'] ?? null;
            $filepath = $data['filepath'] ?? null;

            if (($file === null or $file['tmp_name'] === "") and $filepath === null) {
                $error[] = "Aucun fichier fourni";
            } else {
                if ($filepath === null) {
                    $filepath = '/tmp/import_mission_' . (new DateTime())->getTimestamp() . '.csv';
                    $filename = $file['name'];
                    copy($file['tmp_name'], $filepath);
                }
            }
            if ($data['referentiel'] === '') {
                $error[] = "Aucun référentiel sélectionné";
            }

            if ($data['mode'] and $data['referentiel'] !== '' and $filepath) {
                $mode = $data['mode'];
                if (!in_array($mode, ['preview', 'import'])) {
                    $error[] = "Le mode sélectionné est non valide (" . $mode . " doit être soit 'preview' soit 'import')";
                }

                $array = $this->getFichierService()->readCSV($filepath, true, $separateur);
                if (empty($array)) {
                    $warning[] = "Le fichier ne contient pas de données.";
                } else {

                    // Vérification des colonnes et référentiel ////////////////////////////////////////////////////////////
                    $header = [];
                    foreach ($array[0] as $key => $value) {
                        $header[] = $key;
                    }
                    $hasIdMission = in_array(Mission::MISSION_PRINCIPALE_HEADER_ID, $header);
                    if (!$hasIdMission) $error[] = "La colonne obligatoire [" . Mission::MISSION_PRINCIPALE_HEADER_ID . "] est manquante";
                    $hasLibelle = in_array(Mission::MISSION_PRINCIPALE_HEADER_LIBELLE, $header);
                    if (!$hasLibelle) $error[] = "La colonne obligatoire [" . Mission::MISSION_PRINCIPALE_HEADER_LIBELLE . "] est manquante";
                    $hasActivites = in_array(Mission::MISSION_PRINCIPALE_HEADER_ACTIVITES, $header);
                    if (!$hasActivites) $warning[] = "La colonne facultative [" . Mission::MISSION_PRINCIPALE_HEADER_ACTIVITES . "] est manquante";
                    $hasFamilles = in_array(Mission::MISSION_PRINCIPALE_HEADER_NIVEAU, $header);
                    if (!$hasFamilles) $warning[] = "La colonne facultative [" . Mission::MISSION_PRINCIPALE_HEADER_FAMILLES . "] est manquante";
                    $hasNiveau = in_array(Mission::MISSION_PRINCIPALE_HEADER_NIVEAU, $header);
                    if (!$hasNiveau) $warning[] = "La colonne facultative [" . Mission::MISSION_PRINCIPALE_HEADER_NIVEAU . "] est manquante";
                    $hasCodesFichesMetiers = in_array(Mission::MISSION_PRINCIPALE_HEADER_CODES_EMPLOITYPE, $header);
                    if (!$hasCodesFichesMetiers) $warning[] = "La colonne facultative [" . Mission::MISSION_PRINCIPALE_HEADER_CODES_EMPLOITYPE . "] est manquante";
                    $hasCodesFonctions = in_array(Mission::MISSION_PRINCIPALE_HEADER_CODES_FONCTION, $header);
                    if (!$hasCodesFonctions) $warning[] = "La colonne facultative [" . Mission::MISSION_PRINCIPALE_HEADER_CODES_FONCTION . "] est manquante";

                    $referentiel = $this->getReferentielService()->getReferentiel($data['referentiel']);
                    if ($referentiel === null) $error[] = "Le référentiel n'a pas pu être récupéré.";


                    if ($hasIdMission and $hasLibelle and $referentiel !== null) {
                        $position = 1;

                        foreach ($array as $row) {
                            $position++;
                            try {
                                [$mission, $debug] = $this->getMissionPrincipaleService()->createOneWithCsv($row, $separateur, $referentiel, $position);
                                $missions[] = $mission;
                                if (isset($debug['info'])) {
                                    foreach ($debug['info'] as $line) {
                                        $info[] = $line;
                                    }
                                }
                                if (isset($debug['warning'])) {
                                    foreach ($debug['warning'] as $line) {
                                        $warning[] = $line;
                                    }
                                }
                                if (isset($debug['error'])) {
                                    foreach ($debug['error'] as $line) {
                                        $error[] = $line;
                                    }
                                }
                            } catch (Exception $e) {
                                if ($error !== '') $error[] = $e->getMessage();
                            }
                        }
                    }

                    if ($mode === 'import') {
                        /** @var Mission $mission */
                        foreach ($missions as $mission) {
                            if ($mission->getId() !== null)
                            {
                                $info[] = "La mission [".$mission->getReferentiel()->getLibelleCourt()."|".$mission->getId()."|".$mission->getLibelle()."] existe déjà";
                            }
                            else {
                                //famille professionnelle
                                foreach ($mission->getFamillesProfessionnelles() as $famille) {
                                    $exist = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelleByLibelle($famille->getLibelle());
                                    if (!$exist) {
                                        $this->getFamilleProfessionnelleService()->create($famille);
                                    }
                                }
                                //niveaux
                                if ($mission->getNiveau()) {
                                    $this->getNiveauEnveloppeService()->create($mission->getNiveau());
                                }
                                //activite
                                $activites = [];
                                foreach ($mission->getActivites() as $activite) {
                                    $activites[$activite->getOrdre()] = $activite;
                                }
                                $mission->clearActivites();
                                //mission
                                $this->getMissionPrincipaleService()->create($mission);

                                foreach ($activites as $activite) {
                                    if ($activite->getId()) $this->getMissionActiviteService()->update($activite);
                                    else $this->getMissionActiviteService()->create($activite);
                                    $mission->addMissionActivite($activite);
                                }
                                $this->getMissionPrincipaleService()->update($mission);
                            }

                            // Bricolage pour satisfaire Marseille
                            $codesFicheMetier = explode('|',$mission->getCodesFicheMetier());
                            foreach ($codesFicheMetier as $codeFicheMetier) {
                                $fichemetier = $this->getFicheMetierService()->getFicheMetierByReferentielAndCode($referentiel, $codeFicheMetier);
                                if ($fichemetier === null) { $warning[] = "La fiche metier [".$referentiel->getLibelleCourt()."|".$codeFicheMetier."] n'existe pas"; }
                                else {
                                    if (!$fichemetier->hasMission($mission)) $this->getFicheMetierService()->addMission($fichemetier, $mission);
                                }
                            }
                            $codesFonction = explode('|',$mission->getCodesFonction());
                            foreach ($codesFonction as $codeFonction) {
                                $fichesmetiers = $this->getFicheMetierService()->getFichesMetiersByCodeFonction($codeFonction);
                                if (empty($fichesmetiers)) { $warning[] = "Aucune fiche metier utilise le code [".$codeFonction."]"; }
                                foreach ($fichesmetiers as $fichemetier) {
                                    if (!$fichemetier->hasMission($mission)) $this->getFicheMetierService()->addMission($fichemetier, $mission);
                                }
                            }
                        }
                    }
                }
            }
        }

        return new ViewModel([
            'separateur' => $separateur,

            'missions' => $missions,
            'info' => $info,
            'warning' => $warning,
            'error' => $error,

            'referentiels' => $this->getReferentielService()->getReferentiels(),
            'data' => $data,
            'filepath' => $filepath,
            'filename' => $filename,
        ]);
    }
}