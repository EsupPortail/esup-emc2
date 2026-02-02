<?php

namespace FicheMetier\Controller;

use Application\Form\ModifierLibelle\ModifierLibelleFormAwareTrait;
use Carriere\Entity\Db\NiveauEnveloppe;
use Carriere\Form\NiveauEnveloppe\NiveauEnveloppeFormAwareTrait;
use Carriere\Form\SelectionnerFamillesProfessionnelles\SelectionnerFamillesProfessionnellesFormAwareTrait;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Carriere\Service\Niveau\NiveauServiceAwareTrait;
use Carriere\Service\NiveauEnveloppe\NiveauEnveloppeServiceAwareTrait;
use DateTime;
use Element\Form\SelectionApplication\SelectionApplicationFormAwareTrait;
use Element\Form\SelectionCompetence\SelectionCompetenceFormAwareTrait;
use Element\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Form\MissionPrincipale\MissionPrincipaleFormAwareTrait;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use FicheMetier\Provider\Privilege\FicheMetierPrivileges;
use FicheMetier\Provider\Privilege\MissionPrincipalePrivileges;
use FicheMetier\Service\CodeFonction\CodeFonctionServiceAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\MissionElement\MissionElementServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\JsonModel;
use Laminas\View\Model\ViewModel;
use Referentiel\Provider\Privilege\ReferentielPrivileges;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;
use UnicaenPrivilege\Service\Privilege\PrivilegeServiceAwareTrait;
use UnicaenRenderer\Service\Rendu\RenduServiceAwareTrait;
use UnicaenUtilisateur\Service\User\UserServiceAwareTrait;

class MissionPrincipaleController extends AbstractActionController
{
    use ApplicationElementServiceAwareTrait;
    use CodeFonctionServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use MissionElementServiceAwareTrait;
    use NiveauEnveloppeServiceAwareTrait;
    use NiveauServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use PrivilegeServiceAwareTrait;
    use ReferentielServiceAwareTrait;
    use RenduServiceAwareTrait;
    use UserServiceAwareTrait;

    use MissionPrincipaleFormAwareTrait;
    use ModifierLibelleFormAwareTrait;
    use NiveauEnveloppeFormAwareTrait;
    use SelectionApplicationFormAwareTrait;
    use SelectionCompetenceFormAwareTrait;
    use SelectionnerFamillesProfessionnellesFormAwareTrait;


    public function indexAction(): ViewModel
    {
        $params = $this->params()->fromQuery();
        $missions = $this->getMissionPrincipaleService()->getMissionsPrincipalesWithFiltre($params);
        $dictionnaire = $this->getMissionPrincipaleService()->generateDictionnaireFicheMetier();

        return new ViewModel([
            'missions' => $missions,
            'referentiels' => $this->getReferentielService()->getReferentiels(),
            'familles' => $this->getFamilleProfessionnelleService()->getFamillesProfessionnelles(),
            'dictionnaire' => $dictionnaire,
            'params' => $params,
        ]);
    }

    /** CRUD ****************************************************************************************/

    public function afficherAction(): ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);
        $fichesmetiers = $this->getFicheMetierService()->getFichesMetiersHavingMissionPrincipale($mission);

        $vm = new ViewModel([
            'title' => "Affichage de la mission principale",
            'modification' => false,
            'mission' => $mission,
            'fichesmetiers' => $fichesmetiers,
            'codeFonction' => $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::CODE_FONCTION)
        ]);
        return $vm;
    }

    public function ajouterAction(): ViewModel
    {
        $mission = new Mission();
        $referentiel = $this->getReferentielService()->getReferentielByLibelleCourt('EMC2');
        $mission->setReferentiel($referentiel);

        $form = $this->getMissionPrincipaleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-principale/ajouter', [], [], true));
        $form->bind($mission);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);
            if ($form->isValid()) {
                $niveau = $mission->getNiveau();
                if ($niveau !== null) {
                    if ($niveau->getId() === null) {
                        $this->getNiveauEnveloppeService()->create($niveau);
                    } else {
                        $this->getNiveauEnveloppeService()->update($niveau);
                    }
                }
                $this->getMissionPrincipaleService()->create($mission);
                if ($mission->getReference() === null) {
                    $mission->setReference($mission->getId());
                    $this->getMissionPrincipaleService()->update($mission);
                }
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Ajout d'une mission principale",
            'form' => $form,
        ]);
        $vm->setTemplate('fiche-metier/mission-principale/modifier');
        return $vm;
    }

    public function modifierAction(): ViewModel
    {
        $mission = $this->getMissionPrincipaleService()->getRequestedMissionPrincipale($this);

        $form = $this->getMissionPrincipaleForm();
        $form->setAttribute('action', $this->url()->fromRoute('mission-principale/modifier', ['mission-principale' => $mission?->getId()], [], true));
        $form->bind($mission);

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $form->setData($data);

            if ($form->isValid()) {
                $niveau = $mission->getNiveau();
                if ($niveau !== null) {
                    if ($niveau->getId() === null) {
                        $this->getNiveauEnveloppeService()->create($niveau);
                    } else {
                        $this->getNiveauEnveloppeService()->update($niveau);
                    }
                }
                if ($mission->getReference() === null) {
                    $mission->setReference($mission->getId());
                }
                $this->getMissionPrincipaleService()->update($mission);
                exit();
            }
        }

        $vm = new ViewModel([
            'title' => "Modification de la mission principale",
            'form' => $form,
        ]);
        $vm->setTemplate('fiche-metier/mission-principale/modifier');
        return $vm;
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
            $nbFicheMetier = 0;
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
        $colonnesObligatoires = [Mission::MISSION_PRINCIPALE_HEADER_ID, Mission::MISSION_PRINCIPALE_HEADER_LIBELLE];

        $displayCodeFonction = $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::CODE_FONCTION);

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

            $mode = $data['mode'];
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

            $csvFile = fopen($filepath, "r");
            // lecture du header + position colonne
            if ($csvFile !== false) {
                $header = fgetcsv($csvFile, null, ";");
                // Remove BOM https://stackoverflow.com/questions/39026992/how-do-i-read-a-utf-csv-file-in-php-with-a-bom
                $header = preg_replace(sprintf('/^%s/', pack('H*', 'EFBBBF')), "", $header);
                $nbElements = count($header);

                foreach ($colonnesObligatoires as $colonne) {
                    if (!in_array($colonne, $header)) $error[] = "La colonne obligatoire <strong>" . $colonne . "</strong> est absente.";
                }

                $referentiel = $this->getReferentielService()->getReferentiel($data['referentiel']);
                if ($referentiel === null) $error[] = "Le référentiel n'a pas pu être récupéré.";


                $raws = [];
                while (($row = fgetcsv($csvFile, null, ';')) !== false) {
                    $item = [];
                    for ($position = 0; $position < $nbElements; ++$position) {
                        $item[$header[$position]] = $row[$position];
                    }
                    $raws[] = $item;
                }

                if (empty($error)) {
                    $ligne = 1;
                    foreach ($raws as $raw) {
                        $ligne++;

                        /** Check des valeurs obligatoires **/
                        $allGood = true;
                        foreach ($colonnesObligatoires as $colonne) {
                            if (trim($raw[$colonne]) === "") {
                                $error[] = "La colonne obligatoire <strong>" . $colonne . "</strong> n'a pas de valeur la ligne " . $ligne . " a été ignorée";
                                $allGood = false;
                            }
                        }

                        if ($allGood) {
                            $idOrig = trim($raw[Mission::MISSION_PRINCIPALE_HEADER_ID]);
                            $libelle = trim($raw[Mission::MISSION_PRINCIPALE_HEADER_LIBELLE]);

                            $mission = $this->getMissionPrincipaleService()->getMissionPrincipaleByReference($referentiel, $idOrig);
                            if ($mission === null) {
                                $mission = new Mission();
                                $mission->setReferentiel($referentiel);
                                $mission->setReference($idOrig);
                            }
                            $mission->setLibelle($libelle);

                            if (isset($raw[Mission::MISSION_PRINCIPALE_HEADER_DESCRIPTION])) {
                                $description = $raw[Mission::MISSION_PRINCIPALE_HEADER_DESCRIPTION] ?? trim($raw[Mission::MISSION_PRINCIPALE_HEADER_DESCRIPTION]);
                                $mission->setDescription($description !== '' ? $description : null);
                            }
                            if (isset($raw[Mission::MISSION_PRINCIPALE_HEADER_CODES_EMPLOITYPE])) {
                                $codeEmploiType = $raw[Mission::MISSION_PRINCIPALE_HEADER_CODES_EMPLOITYPE] ?? trim($raw[Mission::MISSION_PRINCIPALE_HEADER_CODES_EMPLOITYPE]);
                                $mission->setCodesFicheMetier($codeEmploiType !== '' ? $codeEmploiType : null);
                            }
                            if (isset($raw[Mission::MISSION_PRINCIPALE_HEADER_CODES_FONCTION])) {
                                $codeFonction = $raw[Mission::MISSION_PRINCIPALE_HEADER_CODES_FONCTION] ?? trim($raw[Mission::MISSION_PRINCIPALE_HEADER_CODES_FONCTION]);
                                $mission->setCodesFonction($codeFonction !== '' ? $codeFonction : null);
                            }

                            /* FAMILLE PROFESSIONNELLE ***********************************************************************************/
                            if (isset($raw[Mission::MISSION_PRINCIPALE_HEADER_FAMILLES]) and trim($raw[Mission::MISSION_PRINCIPALE_HEADER_FAMILLES]) !== '') {
                                $mission->clearFamillesProfessionnelles();
                                $famillesString = explode($separateur, $raw[Mission::MISSION_PRINCIPALE_HEADER_FAMILLES]);
                                foreach ($famillesString as $familleString) {
                                    $famille = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelleByLibelle(trim($familleString));
                                    if ($famille === null) {
//                                        $famille = new FamilleProfessionnelle();
//                                        $famille->setLibelle(trim($familleString));
                                        $warning[] = "La famille professionnelle [" . trim($familleString) . "] n'existe pas (ligne " . $ligne . ").";
                                    } else {
                                        if (!$mission->hasFamilleProfessionnelle($famille)) $mission->addFamilleProfessionnelle($famille);
                                    }
                                }
                            }

                            /** NIVEAUX *******************************************************************************/
                            if (isset($json[Mission::MISSION_PRINCIPALE_HEADER_NIVEAU]) and trim($json[Mission::MISSION_PRINCIPALE_HEADER_NIVEAU]) !== '') {
                                $niveau = explode(":", $json[Mission::MISSION_PRINCIPALE_HEADER_NIVEAU]);
                                if (count($niveau) === 1) {
                                    $niv = $this->getNiveauService()->getNiveauByEtiquette(trim($niveau[0]));
                                    if ($niv === null) {
                                        $debugs['warning'][] = "Le niveau [" . trim($niveau[0]) . "] n'existe pas (ligne " . $ligne . ").";
                                    } else {
                                        $niveau_ = new NiveauEnveloppe();
                                        $niveau_->setBorneInferieure($niv);
                                        $niveau_->setBorneSuperieure($niv);
                                        $mission->setNiveau($niveau_);
                                    }
                                }
                                if (count($niveau) === 2) {
                                    $inf = $this->getNiveauService()->getNiveauByEtiquette(trim($niveau[0]));
                                    if ($inf === null) {
                                        $debugs['warning'][] = "Le niveau [" . trim($niveau[0]) . "] n'existe pas (ligne " . $ligne . ").";
                                    }
                                    $sup = $this->getNiveauService()->getNiveauByEtiquette(trim($niveau[1]));
                                    if ($sup === null) {
                                        $debugs['warning'][] = "Le niveau [" . trim($niveau[1]) . "] n'existe pas (ligne " . $ligne . ").";
                                    }
                                    if ($inf !== null and $sup !== null) {
                                        $niveau_ = new NiveauEnveloppe();
                                        if ($inf->getNiveau() > $sup->getNiveau()) {
                                            $niveau_->setBorneInferieure($sup);
                                            $niveau_->setBorneSuperieure($inf);
                                        }
                                        $niveau_->setBorneInferieure($inf);
                                        $niveau_->setBorneSuperieure($sup);
                                        $mission->setNiveau($niveau_);
                                    }
                                }
                            }

                            $mission->setSourceString(json_encode($raw));

                            $missions[] = $mission;
                        }
                    }

                    if ($mode === 'import') {
                        $info[] = "Importation terminée.";

                        $role = $this->getUserService()->getConnectedRole();
                        $allowedMission = $this->getPrivilegeService()->checkPrivilege(MissionPrincipalePrivileges::MISSIONPRINCIPALE_AFFICHER, $role);
                        $allowedFicheMetier = $this->getPrivilegeService()->checkPrivilege(FicheMetierPrivileges::FICHEMETIER_AFFICHER, $role);
                        $allowedReferentiel = $this->getPrivilegeService()->checkPrivilege(ReferentielPrivileges::REFERENTIEL_AFFICHER, $role);


                        foreach ($missions as $mission) {
                            //niveaux
                            if ($mission->getNiveau()) {
                                $this->getNiveauEnveloppeService()->create($mission->getNiveau());
                            } else  {
                                $this->getNiveauEnveloppeService()->update($mission->getNiveau());
                            }
                            //mission
                            if ($mission->getId() === null) {
                                $this->getMissionPrincipaleService()->create($mission);
                            } else {
                                $this->getMissionPrincipaleService()->update($mission);
                            }

                            // Gestion des codes emploi type ///////////////////////////////////////////////////////
                            $codesFicheMetier = explode($separateur, $mission->getCodesFicheMetier() ?? "");
                            $codesFicheMetier = array_map('trim', $codesFicheMetier);
                            $codesFicheMetier = array_filter($codesFicheMetier, function (string $a) {
                                return $a !== '';
                            });
                            foreach ($codesFicheMetier as $codeFicheMetier) {
                                $fichemetier = $this->getFicheMetierService()->getFicheMetierByReferentielAndCode($referentiel, $codeFicheMetier);
                                if ($fichemetier === null) {
                                    $message = "Aucune fiche métier identifiée " . $codeFicheMetier . " dans le référentiel ";
                                    $message .= $referentiel ? $referentiel->printReference("lien", $this->url()->fromRoute("referentiel", [], [], true), $allowedReferentiel) : "<span class='badge' style='background: grey;'>Aucun référentiel</span>";
                                    $message .= ". ";
                                    $message .= "La mission principale \"" . $mission->getLibelle() . "\" ";
                                    $message .= $mission->printReference("modal", $this->url()->fromRoute('mission-principale/afficher', ['mission-principale' => $mission->getId()], [], true), $allowedMission);
                                    $message .= " ne sera pas ajoutée.";
                                    $warning[] = $message;
                                } else {
                                    if (!$fichemetier->hasMission($mission)) {
                                        $this->getMissionElementService()->addMissionElement($fichemetier, $mission);
                                        $message = "La mission principale \"" . $mission->getLibelle() . "\" ";
                                        $message .= $mission->printReference("modal", $this->url()->fromRoute('mission-principale/afficher', ['mission-principale' => $mission->getId()], [], true), $allowedMission);
                                        $message .= " a été ajoutée à la fiche métier \"" . $fichemetier->getLibelle() . "\" ";
                                        $message .= $fichemetier->printReference("lien", $this->url()->fromRoute('fiche-metier/afficher', ['fiche-metier' => $fichemetier->getId()], [], true), $allowedFicheMetier);
                                        $info[] = $message;
                                    }
                                }
                            }

                            // Gestion des codes fonction //////////////////////////////////////////////////////////
                            $codesFonction = explode('|', $mission->getCodesFonction() ?? "");
                            $codesFonction = array_map('trim', $codesFonction);
                            $codesFonction = array_filter($codesFonction, function (string $a) {
                                return $a !== '';
                            });
                            foreach ($codesFonction as $codeFonction) {
                                $codeFonction_ = $this->getCodeFonctionService()->getCodeFonctionByCode($codeFonction);
                                if ($codeFonction_ === null) {
                                    $message = "Le code fonction <code>" . $codeFonction . "</code> n’existe pas. ";
                                    $message .= "La mission principale \"" . $mission->getLibelle() . "\" ";
                                    $message .= $mission->printReference("modal", $this->url()->fromRoute('mission-principale/afficher', ['mission-principale' => $mission->getId()], [], true), $allowedMission);
                                    $message .= " ne peut pas être ajoutée.";
                                    $warning[] = $message;
                                } else {
                                    $fichesmetiers = $this->getFicheMetierService()->getFichesMetiersByCodeFonction($codeFonction);
                                    if (empty($fichesmetiers)) {
                                        $warning[] = "Aucune fiche métier utilise le code fonction <code>" . $codeFonction . "</code> ; la mission principale ne sera ajoutée à aucune fiche métier.";
                                    }
                                    foreach ($fichesmetiers as $fichemetier) {
                                        if (!$fichemetier->hasMission($mission)) {
                                            $this->getMissionElementService()->addMissionElement($fichemetier, $mission);
                                            $message = "La mission principale \"" . $mission->getLibelle() . "\" ";
                                            $message .= $mission->printReference("modal", $this->url()->fromRoute('mission-principale/afficher', ['mission-principale' => $mission->getId()], [], true), $allowedMission);
                                            $message .= "a été ajoutée à la fiche métier \"" . $fichemetier->getLibelle() . "\" ";
                                            $message .= $fichemetier->printReference("lien", $this->url()->fromRoute('fiche-metier/afficher', ['fiche-metier' => $fichemetier->getId()], [], true), $allowedFicheMetier);
                                            $info[] = $message;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }


        return new ViewModel([
            'separateur' => $separateur,
            'displayCodeFonction' => $displayCodeFonction,

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