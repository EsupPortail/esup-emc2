<?php

namespace FicheMetier\Controller;

use Application\Provider\Etat\FicheMetierEtats;
use Carriere\Entity\Db\FamilleProfessionnelle;
use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Carriere\Service\Niveau\NiveauServiceAwareTrait;
use Carriere\Service\NiveauFonction\NiveauFonctionServiceAwareTrait;
use DateTime;
use Element\Entity\Db\CompetenceElement;
use Element\Entity\Db\CompetenceType;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use FicheMetier\Entity\Db\CodeFonction;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\FicheMetierMission;
use FicheMetier\Entity\Db\TendanceElement;
use FicheMetier\Entity\Db\TendanceType;
use FicheMetier\Form\FicheMetierImportation\FicheMetierImportationFormAwareTrait;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use FicheMetier\Service\CodeFonction\CodeFonctionServiceAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\FicheMetierMission\FicheMetierMissionServiceAwareTrait;
use FicheMetier\Service\Import\ImportServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use FicheMetier\Service\TendanceElement\TendanceElementServiceAwareTrait;
use FicheMetier\Service\TendanceType\TendanceTypeServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Referentiel\Entity\Db\Referentiel;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class ImportController extends AbstractActionController
{
    use ImportServiceAwareTrait;

    use CodeFonctionServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;
    use CorrespondanceServiceAwareTrait;
    use EtatInstanceServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use FicheMetierMissionServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use NiveauServiceAwareTrait;
    use NiveauFonctionServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use ReferentielServiceAwareTrait;
    use TendanceElementServiceAwareTrait;
    use TendanceTypeServiceAwareTrait;

    use FicheMetierImportationFormAwareTrait;

    const FORMAT_RMFP = 'FORMAT_RMFP';
    const FORMAT_REFERENS3 = 'FORMAT_REFERENS3';

    // RMFP ///////////////////////////////////////////////////////////////////////////////////////
    const HEADER_RMFP_REFERENCE = "Code";
    const HEADER_RMFP_LIBELLE = "Intitulé";
    const HEADER_RMFP_MISSION_LIBELLE = "Définition synthétique de l'ER";
    const HEADER_RMFP_MISSION_ACTIVITE = "Activités de l'ER";
    const HEADER_RMFP_FAMILLE = "Famille";
    const HEADER_RMFP_SPECIALITE = "DF";
    const HEADER_RMFP_COMPETENCE_CONNAISSANCE = "Connaissances";
    const HEADER_RMFP_COMPETENCE_OPERATIONNELLE = "Savoir-faire";
    const HEADER_RMFP_COMPETENCE_COMPORTEMENTALE = "Savoir-être";
    const HEADER_RMFP_TENDANCE = "Tendance / évolution";
    const HEADER_RMFP_IMPACT = "Impact sur l'ER";
    const HEADER_RMFP_MANAGEMENT = "Libellé compétence managériale";
    const HEADER_RMFP_CONDITION = "Conditions particulières d'exercice / d'accès";

    // REFERENS 3 /////////////////////////////////////////////////////////////////////////////////
    const HEADER_REFERENS3_REFERENCE = "Code emploi type";
    const HEADER_REFERENS3_LIBELLE = "Intitulé de l’emploi type";
    const HEADER_REFERENS3_MISSION_LIBELLE = "Mission";
    const HEADER_REFERENS3_MISSION_ACTIVITE = "Activités principales";
    const HEADER_REFERENS3_FAMILLE_LIBELLE = "Famille d’activité professionnelle";
    const HEADER_REFERENS3_FAMILLE_POSITION = "TriFAP";
    const HEADER_REFERENS3_SPECIALITE_LIBELLE = "Branche d’activité professionnelle";
    const HEADER_REFERENS3_SPECIALITE_CODE = "Code de la branche d’activité professionnelle";
    const HEADER_REFERENS3_COMPETENCE = "COMPETENCES_ID";
    const HEADER_REFERENS3_FORMATION_DEMANDE = "Domaine de formation souhaité/exigé";
    const HEADER_REFERENS3_FORMATION_DIPLOME = "Diplôme réglementaire exigé - Formation professionnelle si souhaitable";
    const HEADER_REFERENS3_CONDITION = "Conditions particulières d’exercices";
    const HEADER_REFERENS3_TENDANCE = "Facteurs d’évolution à moyen terme";
    const HEADER_REFERENS3_IMPACT = "Impacts sur l’emploi-type";
    const HEADER_REFERENS3_CORRESPONDANCE_STATUTAIRE_LIBELLE = "Correspondance statutaire";
    const HEADER_REFERENS3_CORRESPONDANCE_STATUTAIRE_CODE = "Code de la correspondance statutaire";
    const HEADER_REFERENS3_CORRESPONDANCE_STATUTAIRE_NIVEAU = "Id de la correspondance statutaire";
    const HEADER_REFERENS3_METIERS = "Métiers";
    const HEADER_REFERENS3_CATEGORIE = "REFERENS_CATEGORIE_EMPLOI";
    const HEADER_REFERENS3_URL = "FICHE_URL";
    const HEADER_REFERENS3_PDF = "Fiche au format pdf";

    // HEADER SUPPLEMENTAIRE ///////////////////////////////////////////////////////////////////////////////////////////
    const HEADER_CODE_FONCTION = "Code Fonction";

    public function importAction(): ViewModel
    {
        $form = $this->getFicheMetierImportationForm();
        $form->setAttribute("action", $this->url()->fromRoute("fiche-metier/import"));

        $fiches = [];
        $info = [];
        $warning = [];
        $error = [];

        $referentiel = null;
        $mode = null;
        $fichier_path = null;

        $data = null;
        $file = null;

        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $request->getPost();
            $mode = ($data['mode'] === 'import') ? 'import' : 'preview';
            $files = $request->getFiles()->toArray();
            $file = !empty($files) ? current($files) : null;

            $filename = $data['filename'] ?? null;
            $filepath = $data['filepath'] ?? null;

            if (($file === null or $file['tmp_name'] === "") and $filepath === null) {
                $error[] = "Aucun fichier fourni";
            } else {
                if ($filepath === null) {
                    $filepath = '/tmp/import_fichemetier_' . (new DateTime())->getTimestamp() . '.csv';
                    $filename = $file['name'];
                    copy($file['tmp_name'], $filepath);
                }
            }

            if ($data['referentiel'] === "") {
                $error[] = "Aucun référentiel selectionné";
            } else {
                $referentiel = $this->getReferentielService()->getReferentiel($data['referentiel']);
            }

            if ($data['format'] === ImportController::FORMAT_RMFP) {
                $result = $this->readAsRmfp($data, $filepath, $referentiel, 0);
                /** @var FicheMetier[] $fiches */
                $fiches = $result["fiches"];
                foreach ($result['warning'] as $item) {
                    $warning[] = $item;
                }
                foreach ($result['error'] as $item) {
                    $error[] = $item;
                }
                foreach ($result['info'] as $item) {
                    $info[] = $item;
                }
            }

            if ($data['format'] === ImportController::FORMAT_REFERENS3) {
                $result = $this->readAsReferens($data, $filepath, $referentiel, 100);
                /** @var FicheMetier[] $fiches */
                $fiches = $result['fiches'];
                foreach ($result['warning'] as $item) {
                    $warning[] = $item;
                }
                foreach ($result['error'] as $item) {
                    $error[] = $item;
                }
                foreach ($result['info'] as $item) {
                    $info[] = $item;
                }
            }

            $referentielId = (isset($data["referentiel"]) and $data["referentiel"] !== "") ? $data["referentiel"] : null;
            $referentiel = $this->getReferentielService()->getReferentiel($referentielId);

            if ($mode === 'import') {
                // recuperation des nouvelles familles professionnelles
                foreach ($fiches as $fiche) {
                    if ($fiche->getFamilleProfessionnelle() and $fiche->getFamilleProfessionnelle()->getCorrespondance() and $fiche->getFamilleProfessionnelle()->getCorrespondance()->getId() === null) {
                        $fiche->getFamilleProfessionnelle()->getCorrespondance()->setId(100000 + rand(0, 100000));
                        $this->getCorrespondanceService()->create($fiche->getFamilleProfessionnelle()->getCorrespondance());
                    }
                }
                foreach ($fiches as $fiche) {
                    if ($fiche->getFamilleProfessionnelle() and $fiche->getFamilleProfessionnelle()->getId() === null) {
                        $this->getFamilleProfessionnelleService()->create($fiche->getFamilleProfessionnelle());
                    }
                }
                $a = 1;
                foreach ($fiches as $fiche) {
                    if ($fiche->getCodeFonction() and $fiche->getCodeFonction()->getId() === null) {
                        $this->getCodeFonctionService()->create($fiche->getCodeFonction());
                    }
                }


                /** @var FicheMetier $fiche */
                foreach ($fiches as $fiche) {
                    $elements = $fiche->getCompetenceCollection()->toArray();
                    $fiche->clearCompetences();
                    $tendances = $fiche->getTendances();
                    $fiche->clearTendances();
                    $missions = $fiche->getMissions();
                    $fiche->clearMissions();

                    if ($fiche->getId() === null) $this->getFicheMetierService()->create($fiche);

                    foreach ($elements as $element) {
                        if ($element->getId() === null) $this->getCompetenceElementService()->create($element);
                        else $this->getCompetenceElementService()->update($element);

                        $fiche->addCompetenceElement($element);
                    }

                    foreach ($tendances as $tendance) {
                        $tendance->setFicheMetier($fiche);
                        if ($tendance->getId() === null) $this->getTendanceElementService()->create($tendance);
                        else $this->getTendanceElementService()->update($tendance);

                        $fiche->addTendance($tendance);
                    }

                    foreach ($missions as $mission) {
                        if ($mission->getId() === null) $this->getFicheMetierMissionService()->deepCreate($mission);
                        else {
                            foreach ($mission->getMission()->getActivites() as $activite) {
                                if ($activite->getId() === null) $this->getMissionActiviteService()->create($activite);
                                else {
                                    if ($activite->getOrdre() === -1) $this->getMissionActiviteService()->delete($activite);
                                    else $this->getMissionActiviteService()->update($activite);
                                }
                            }
                        }
                        $fiche->addMission($mission);
                    }

                    $this->getEtatInstanceService()->setEtatActif($fiche, FicheMetierEtats::ETAT_VALIDE, FicheMetierEtats::TYPE);
                    $this->getFicheMetierService()->update($fiche);
                }
            }
        }
        if ($referentiel === null) $error[] = "Aucun référentiel de sélectionné";
        else $form->get('referentiel')->setValue($referentiel->getId());
        if ($mode === null) $error[] = "Aucun mode d'importation de sélectionné";
        else $form->get('mode')->setValue($mode);
        if ($fichier_path === "") $error[] = "Aucun fichier fourni";
        else {
            $form->get('fichier')->setValue($data['fichier'] ?? null);
        }

        return new ViewModel([
            'form' => $form,
            'info' => $info,
            'warning' => $warning,
            'error' => $error,
            'fiches' => $fiches,

            'displayCodeFonction' => $this->getParametreService()->getValeurForParametre(FicheMetierParametres::TYPE, FicheMetierParametres::CODE_FONCTION),
            'referentiels' => $this->getReferentielService()->getReferentiels(),
            'data' => $data,
            'file' => $file,
            'filepath' => $filepath ?? null,
            'filename' => $filename ?? null,
        ]);
    }

    public function readAsReferens($data, string $filepath, Referentiel $referentiel, int $verbosity = 0): array
    {
        $error = [];
        $warning = [];
        $info = [];
        $fiches = [];

        /** Préparation pour le traitement des compétences */
        $dictionnaireCompetence = $this->getCompetenceService()->generateDictionnaire($referentiel, 'reference');
        $tendanceImpact = $this->getTendanceTypeService()->getTendanceTypeByCode(TendanceType::IMPACT);
        $tendanceFacteur = $this->getTendanceTypeService()->getTendanceTypeByCode(TendanceType::FACTEUR);
        $tendanceCondition = $this->getTendanceTypeService()->getTendanceTypeByCode(TendanceType::CONDITIONS);
        if ($tendanceImpact === null) {
            $warning[] = "Aucune type de tendance [" . TendanceType::IMPACT . "] les informations contenues dans la colonne [Impact sur l'ER] ne seront pas prise en compte";
        }
        if ($tendanceFacteur === null) {
            $warning[] = "Aucune type de tendance [" . TendanceType::FACTEUR . "] les informations contenues dans la colonne [Tendance / évolution] ne seront pas prise en compte";
        }
        if ($tendanceCondition === null) {
            $warning[] = "Aucune type de tendance [" . TendanceType::CONDITIONS . "] les informations contenues dans la colonne [Tendance / évolution] ne seront pas prise en compte";
        }

        /** Préparation pour les niveaus de carrière */
        $dictionnaireNiveauCarriere = $this->getNiveauService()->generateDictionnaire();
        /** Préparation pour les codes fonctions  */
        $dictionnaireCodeFonction = $this->getCodeFonctionService()->generateDictionnaire();


        $debut = (new DateTime())->getTimestamp();

        $csvFile = fopen($filepath, "r");
        // lecture du header + position colonne
        if ($csvFile !== false) {
            $header = fgetcsv($csvFile, null, ";");
            // Remove BOM https://stackoverflow.com/questions/39026992/how-do-i-read-a-utf-csv-file-in-php-with-a-bom
            $header = preg_replace(sprintf('/^%s/', pack('H*', 'EFBBBF')), "", $header);
            $nbElements = count($header);

            if ($verbosity > 10000) {
                var_dump($nbElements . " colonnes dans le header");
                var_dump($header);
            }

            $raws = [];
            while (($row = fgetcsv($csvFile, null, ';')) !== false) {
                $item = [];
                for ($position = 0; $position < $nbElements; ++$position) {
                    $item[$header[$position]] = $row[$position];
                }
                $raws[] = $item;
            }

            foreach ($raws as $raw) {
                // Intitulé + (domaine ...)
                $intitule = $raw[self::HEADER_REFERENS3_LIBELLE] ?? "";
                $code = $raw[self::HEADER_REFERENS3_REFERENCE] ?? "";

                $fiche = $this->getFicheMetierService()->getFicheMetierByReferentielAndCode($referentiel, $code);
                if ($fiche === null) {
                    $fiche = new FicheMetier();
                }
                $fiche->setRaw(json_encode($raw));
                $fiche->setLibelle($intitule);
                $fiche->setReference($code);
                $fiche->setReferentiel($referentiel);

                //specialité
                $specialite = $this->getCorrespondanceService()->getCorrespondanceByTypeAndCode('BAP', trim($raw[self::HEADER_REFERENS3_SPECIALITE_CODE] ?? ""));
                if ($specialite === null) {
                    $specialite = $this->getCorrespondanceService()->createWith('BAP', trim($raw[self::HEADER_REFERENS3_SPECIALITE_CODE] ?? ""), trim($raw[self::HEADER_REFERENS3_SPECIALITE_LIBELLE] ?? ""), false);
                    $specialite->setId(null);
                }
                //famille
                $famille = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelleByLibelle($raw[self::HEADER_REFERENS3_FAMILLE_LIBELLE] ?? "");
                if ($famille === null) {
                    $famille = new FamilleProfessionnelle();
                    $famille->setLibelle($raw[self::HEADER_REFERENS3_FAMILLE_LIBELLE] ?? "");
                    $famille->setPosition($raw[self::HEADER_REFERENS3_FAMILLE_POSITION] ?? "");
                    $famille->setCorrespondance($specialite);
                } else {
                    if ($famille->getCorrespondance() !== $specialite) $warning[] = "La famille professionnelle [" . ($raw[self::HEADER_REFERENS3_FAMILLE_LIBELLE] ?? "") . "] n'a pas la spécialité connue par EMC2 [" . $famille->getCorrespondance()?->getCategorie() . "!=" . ($raw[self::HEADER_REFERENS3_SPECIALITE_CODE] ?? "") . "]";
                    if ($famille->getPosition() != ($raw[self::HEADER_REFERENS3_FAMILLE_POSITION] ?? "")) $warning[] = "La famille professionnelle [" . ($raw[self::HEADER_REFERENS3_FAMILLE_LIBELLE] ?? "") . "] n'a pas la position connue par EMC2 [" . $famille->getPosition() . "!=" . ($raw[self::HEADER_REFERENS3_FAMILLE_POSITION] ?? "") . "]";
                }
                $fiche->setFamilleProfessionnelle($famille);
                //Niveau de carriere
                $niveauCarriereId = (isset($raw[self::HEADER_REFERENS3_CORRESPONDANCE_STATUTAIRE_NIVEAU]) and trim($raw[self::HEADER_REFERENS3_CORRESPONDANCE_STATUTAIRE_NIVEAU]) !== '') ? trim($raw[self::HEADER_REFERENS3_CORRESPONDANCE_STATUTAIRE_NIVEAU]) : null;
                if ($niveauCarriereId !== null) {
                    if (isset($dictionnaireNiveauCarriere[$niveauCarriereId])) {
                        $fiche->setNiveauCarriere($dictionnaireNiveauCarriere[$niveauCarriereId]);
                    } else {
                        $warning[] = "Aucun niveau de carrière de connue pour le niveau " . $niveauCarriereId . " " . ($raw[self::HEADER_REFERENS3_CORRESPONDANCE_STATUTAIRE_CODE]) ?? "Colonne manquante [" . self::HEADER_REFERENS3_CORRESPONDANCE_STATUTAIRE_CODE . "]";
                    }
                }

                //code fonction
                $codeFonction = (isset($raw[self::HEADER_CODE_FONCTION]) and trim($raw[self::HEADER_CODE_FONCTION]) !== '') ? trim($raw[self::HEADER_CODE_FONCTION]) : null;
                if ($codeFonction !== null) {
                    if (isset($dictionnaireCodeFonction[$codeFonction])) {
                        $fiche->setCodeFonction($dictionnaireCodeFonction[$codeFonction]);
                    } else {
                        $code = $this->createCodeFonction($codeFonction, $warning);
                        $fiche->setCodeFonction($code);
                    }
                }

                /** Liens *********************************************************************************************/

                $lienWeb = isset($raw[self::HEADER_REFERENS3_URL]) ? trim($raw[self::HEADER_REFERENS3_URL]) : null;
                if ($lienWeb !== null and $lienWeb !== '') $fiche->setLienWeb($lienWeb);

                $lienPdf = isset($raw[self::HEADER_REFERENS3_PDF]) ? trim($raw[self::HEADER_REFERENS3_PDF]) : null;
                if ($lienPdf !== null and $lienPdf !== '') $fiche->setLienPdf($lienPdf);

                /** Partie Mission et activités ***********************************************************************/

                $missionLibelle = $raw[self::HEADER_REFERENS3_MISSION_LIBELLE] ?? "";
                $activites = explode("|", $raw[self::HEADER_REFERENS3_MISSION_ACTIVITE] ?? "");

                $ficheMission = $fiche->getMissionByReference($fiche->getReferentiel(), $fiche->getReference());
                if ($ficheMission === null) {
                    $mission = $this->getMissionPrincipaleService()->createWith($missionLibelle, $activites, false);
                    $mission->setReferentiel($referentiel);
                    $mission->setReference($fiche->getReference());

                    $ficheMission = new FicheMetierMission();
                    $ficheMission->setMission($mission);
                    $ficheMission->setFicheMetier($fiche);
                    $ficheMission->setOrdre(1);

                    $fiche->addMission($ficheMission);
                } else {
                    $mission = $ficheMission->getMission();
                    $mission->setReferentiel($referentiel);
                    $mission->setReference($fiche->getReference());

                    $mission->setLibelle($missionLibelle);
                    $this->getMissionPrincipaleService()->update($mission);
                }

                /** COMPETENCES ***************************************************************************************/
                $comptenceIds = explode("|", $raw[self::HEADER_REFERENS3_COMPETENCE] ?? "");
                $dictionnaireFicheMetierCompetence = [];
                $competences = $fiche->getCompetenceListe();
                foreach ($competences as $competence) {
                    $dictionnaireFicheMetierCompetence[$competence->getCompetence()->getId()] = $competence;
                }
                $fiche->clearCompetences();
                foreach ($comptenceIds as $comptenceId) {
                    if (isset($dictionnaireFicheMetierCompetence[$comptenceId])) {
                        $fiche->addCompetenceElement($dictionnaireFicheMetierCompetence[$comptenceId]);
                    } else {
                        $competence = $dictionnaireCompetence[$comptenceId] ?? null;
                        if ($competence !== null) {
                            $element = new CompetenceElement();
                            $element->setCompetence($competence);
                            $fiche->addCompetenceElement($element);
                        } else {
                            $warning[] = "[" . $comptenceId . "] compétence non trouvée.";
                        }
                    }
                }

                /** TENDANCE ******************************************************************************************/

                $tendances = $fiche->getTendances();
                $tendancesListing = [
                    ['type' => $tendanceFacteur, 'colonne' => self::HEADER_REFERENS3_TENDANCE],
                    ['type' => $tendanceImpact, 'colonne' => self::HEADER_REFERENS3_IMPACT],
                    ['type' => $tendanceCondition, 'colonne' => self::HEADER_REFERENS3_CONDITION],
                ];
                foreach ($tendancesListing as $tendanceType) {
                    $type = $tendanceType['type'];
                    $colonne = $tendanceType['colonne'];
                    if ($type and isset($raw[$colonne]) and trim($raw[$colonne]) !== "") {
                        $tendance = null;
                        if (isset($tendances[$type->getCode()])) {
                            $tendance = $tendances[$type->getCode()];
                            $tendance->setTexte(str_replace("|", "<br>", trim($raw[$colonne])));
                        } else {
                            $tendance = new TendanceElement();
                            $tendance->setType($type);
                            $tendance->setTexte(str_replace("|", "<br>", trim($raw[$colonne])));
                            $fiche->addTendance($tendance);
                        }
                    }
                }
                $fiches[] = $fiche;
            }
        }

        fclose($csvFile);
        $fin = (new DateTime())->getTimestamp();
        $info[] = "Temps de traitement : " . max(1, ($fin - $debut)) . " seconde·s";

        return ['fiches' => $fiches, 'warning' => $warning, 'info' => $info, 'error' => $error];
    }

    public function readAsRmfp($data, string $filepath, Referentiel $referentiel, int $verbosity = 0): array
    {
        $warning = [];
        $error = [];
        $info = [];
        $fiches = [];

        /** Préparation pour le traitement des compétences */
        $tConnaissance = $this->getCompetenceTypeService()->getCompetenceTypeByCode(CompetenceType::CODE_CONNAISSANCE);
        $tsavoiretre = $this->getCompetenceTypeService()->getCompetenceTypeByCode(CompetenceType::CODE_COMPORTEMENTALE);
        $tsavoirfaire = $this->getCompetenceTypeService()->getCompetenceTypeByCode(CompetenceType::CODE_OPERATIONNELLE);

        $dictionnaireConnaissances = $this->getCompetenceService()->generateDictionnaire($referentiel, 'libelle', $tConnaissance);
        $dictionnaireSavoirFaire = $this->getCompetenceService()->generateDictionnaire($referentiel, 'libelle', $tsavoirfaire);
        $dictionnaireSavoirEtre = $this->getCompetenceService()->generateDictionnaire($referentiel, 'libelle', $tsavoiretre);
        $tendanceImpact = $this->getTendanceTypeService()->getTendanceTypeByCode(TendanceType::IMPACT);
        $tendanceFacteur = $this->getTendanceTypeService()->getTendanceTypeByCode(TendanceType::FACTEUR);
        $tendanceCondition = $this->getTendanceTypeService()->getTendanceTypeByCode(TendanceType::CONDITIONS);
        if ($tendanceImpact === null) {
            $warning[] = "Aucune type de tendance [" . TendanceType::IMPACT . "] les informations contenues dans la colonne [Impact sur l'ER] ne seront pas prise en compte";
        }
        if ($tendanceFacteur === null) {
            $warning[] = "Aucune type de tendance [" . TendanceType::FACTEUR . "] les informations contenues dans la colonne [Tendance / évolution] ne seront pas prise en compte";
        }
        if ($tendanceCondition === null) {
            $warning[] = "Aucune type de tendance [" . TendanceType::CONDITIONS . "] les informations contenues dans la colonne [Tendance / évolution] ne seront pas prise en compte";
        }

        $mode = ($data['mode'] === 'import') ? 'import' : 'preview';
        $debut = (new DateTime())->getTimestamp();


        /** Préparation pour les codes fonctions  */
        $dictionnaireCodeFonction = $this->getCodeFonctionService()->generateDictionnaire();

        $csvFile = fopen($filepath, "r");
        // lecture du header + position colonne
        if ($csvFile !== false) {
            $header = fgetcsv($csvFile, null, ";");
            // Remove BOM https://stackoverflow.com/questions/39026992/how-do-i-read-a-utf-csv-file-in-php-with-a-bom
            $header = preg_replace(sprintf('/^%s/', pack('H*', 'EFBBBF')), "", $header);
            $nbElements = count($header);

            if ($verbosity > 0) {
                var_dump($nbElements . " colonnes dans le header");
                var_dump($header);
            }

            //lecture des fiches
            $raws = [];
            while (($row = fgetcsv($csvFile, null, ';')) !== false) {
                $item = [];
                for ($position = 0; $position < $nbElements; ++$position) {
                    $item[$header[$position]] = $row[$position];
                }
                $raws[] = $item;
            }
            $nbFiches = count($raws);
            if ($verbosity > 0) {
                var_dump($nbFiches . " fiches dans le csv");
            }

            foreach ($raws as $raw) {
                // Intitulé + (domaine ...)
                $intitule = $raw[self::HEADER_RMFP_LIBELLE] ?? "";
                $code = $raw[self::HEADER_RMFP_REFERENCE] ?? "";
                $fiche = $this->getFicheMetierService()->getFicheMetierByReferentielAndCode($referentiel, $code);

                if ($fiche === null) {
                    $fiche = new FicheMetier();
                }
                $fiche->setRaw(json_encode($raw));
                $fiche->setLibelle($intitule);
                $fiche->setReference($code);
                $fiche->setReferentiel($referentiel);
//                $this->getFicheMetierService()->create($fiche);

                $famille = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelleByLibelle(trim($raw[self::HEADER_RMFP_FAMILLE] ?? ""));
                $newFamille = false;
                if ($famille === null) {
                    $famille = new FamilleProfessionnelle();
                    $famille->setLibelle(trim($raw[self::HEADER_RMFP_FAMILLE] ?? ""));
                    $newFamille = true;
                }
                $fiche->setFamilleProfessionnelle($famille);
                $specialite = $this->getCorrespondanceService()->getCorrespondanceByTypeCodeAndLibelle('DF', trim($raw[self::HEADER_RMFP_SPECIALITE] ?? ""));
                if ($specialite === null) {
                    $specialite = $this->getCorrespondanceService()->createWith('DF', trim($raw[self::HEADER_RMFP_SPECIALITE] ?? ""), trim($raw[self::HEADER_RMFP_SPECIALITE] ?? ""), false);
                    $specialite->setId(null);
                }
                if ($newFamille) $famille->setCorrespondance($specialite);
                elseif ($famille->getCorrespondance() !== $specialite) {
                    $warning[] = "La spécialité attaché à la famille professionnelle [" . $famille->getLibelle() . "] ne correspond pas à la spécialité connue.";
                }

                //code fonction
                $codeFonction = (isset($raw[self::HEADER_CODE_FONCTION]) and trim($raw[self::HEADER_CODE_FONCTION]) !== '') ? trim($raw[self::HEADER_CODE_FONCTION]) : null;
                if ($codeFonction !== null) {
                    if (isset($dictionnaireCodeFonction[$codeFonction])) {
                        $fiche->setCodeFonction($dictionnaireCodeFonction[$codeFonction]);
                    } else {
                        $code = $this->createCodeFonction($codeFonction, $warning);
                        $fiche->setCodeFonction($code);
                    }
                }

                /** Partie Mission et activités ***********************************************************************/

                // !! quid !!
                // > revoir un peu la fiche pour avoir une définition + une liste d'activité sans mission ?
                // > fiche metier devrait pouvoir avoir des activités hors mission ?

                $missionLibelle = $raw[self::HEADER_RMFP_MISSION_LIBELLE];
                $activites = explode("\n", $raw[self::HEADER_RMFP_MISSION_ACTIVITE]);

                $ficheMission = $fiche->getMissionByReference($fiche->getReferentiel(), $fiche->getReference());
                if ($ficheMission === null) {
                    $mission = $this->getMissionPrincipaleService()->createWith($missionLibelle, $activites, false);
                    $mission->setReferentiel($referentiel);
                    $mission->setReference($fiche->getReference());

                    $ficheMission = new FicheMetierMission();
                    $ficheMission->setMission($mission);
                    $ficheMission->setFicheMetier($fiche);
                    $ficheMission->setOrdre(1);

                    $fiche->addMission($ficheMission);
                } else {
                    $mission = $ficheMission->getMission();
                    $mission->setReferentiel($referentiel);
                    $mission->setReference($fiche->getReference());

                    $mission->setLibelle($missionLibelle);
                    $this->getMissionPrincipaleService()->update($mission);
                }


                /** COMPETENCES ***************************************************************************************/

                //todo ajouter le type pour eviter les homonymies
                //todo ajouter un boolean pour la recherche parmi les synonymes

                $connaissances = self::explodeAndTrim($raw[self::HEADER_RMFP_COMPETENCE_CONNAISSANCE] ?? "", "\n");
                foreach ($connaissances as $item) {
//                    $connaissance = $this->getCompetenceService()->getCompetenceByRefentielAndLibelle($rmfp, $item, $tConnaissance);
                    $connaissance = $dictionnaireConnaissances[$item] ?? null;
                    if ($connaissance !== null) {
                        $element = new CompetenceElement();
                        $element->setCompetence($connaissance);
                        $fiche->addCompetenceElement($element);
                    } else {
                        $warning[] = "[" . $item . "] compétence de type [" . $tConnaissance->getLibelle() . "] non trouvée.";
                    }
                }
                $savoiretres = self::explodeAndTrim($raw[self::HEADER_RMFP_COMPETENCE_COMPORTEMENTALE] ?? "", "\n");
                foreach ($savoiretres as $item) {
//                    $savoiretre = $this->getCompetenceService()->getCompetenceByRefentielAndLibelle($rmfp, $item,$tsavoiretre);
                    $savoiretre = $dictionnaireSavoirEtre[$item] ?? null;
                    if ($savoiretre !== null) {
                        $element = new CompetenceElement();
                        $element->setCompetence($savoiretre);
//                        $this->getCompetenceElementService()->create($element);
                        $fiche->addCompetenceElement($element);
                    } else {
                        $warning[] = "[" . $item . "] compétence de type [" . $tsavoiretre->getLibelle() . "] non trouvée.";
                    }
                }
                $savoirfaires = self::explodeAndTrim($raw[self::HEADER_RMFP_COMPETENCE_OPERATIONNELLE] ?? "", "\n");
                foreach ($savoirfaires as $item) {
//                    $savoirfaire = $this->getCompetenceService()->getCompetenceByRefentielAndLibelle($rmfp, $item, $tsavoirfaire);
                    $savoirfaire = $dictionnaireSavoirFaire[$item] ?? null;
                    if ($savoirfaire !== null) {
                        $element = new CompetenceElement();
                        $element->setCompetence($savoirfaire);
//                        $this->getCompetenceElementService()->create($element);
                        $fiche->addCompetenceElement($element);
                    } else {
                        $warning[] = "[" . $item . "] compétence de type [" . $tsavoirfaire->getLibelle() . "] non trouvée.";
                    }
                }
//                $this->getFicheMetierService()->update($fiche);

                /** TENDANCE ******************************************************************************************/

                $tendances = $fiche->getTendances();
                $tendancesListing = [
                    ['type' => $tendanceFacteur, 'colonne' => self::HEADER_RMFP_TENDANCE],
                    ['type' => $tendanceImpact, 'colonne' => self::HEADER_RMFP_IMPACT],
                    ['type' => $tendanceCondition, 'colonne' => self::HEADER_RMFP_CONDITION],
                ];
                foreach ($tendancesListing as $tendanceType) {
                    $type = $tendanceType['type'];
                    $colonne = $tendanceType['colonne'];
                    if ($type and isset($raw[$colonne]) and trim($raw[$colonne]) !== "") {
                        $tendance = null;
                        if (isset($tendances[$type->getCode()])) {
                            $tendance = $tendances[$type->getCode()];
                            $tendance->setTexte(str_replace("|", "<br>", trim($raw[$colonne])));
                        } else {
                            $tendance = new TendanceElement();
                            $tendance->setType($type);
                            $tendance->setTexte(str_replace("|", "<br>", trim($raw[$colonne])));
                            $fiche->addTendance($tendance);
                        }
                    }
                }
                $fiches[] = $fiche;

            }

        }
        fclose($csvFile);
        $fin = (new DateTime())->getTimestamp();
        $temps = "";
        if ($mode === 'preview') $temps .= "<span class='icon icon-checked'></span> Prévisualisation réalisée en ";
        if ($mode === 'import') $temps .= "<span class='icon icon-checked'></span> Importation réalisée en ";
        if ($temps === "") $temps .= "<span class='icon icon-checked'></span> Traitement réalisée en ";
        $temps .= max(1, ($fin - $debut)) . " seconde·s";
        $info[] = $temps;

        return ['fiches' => $fiches, 'error' => $error, 'warning' => $warning, 'info' => $info];
    }

    /** @return string[] */
    static public function explodeAndTrim(?string $listing, string $separateur = "\n"): array
    {
        if ($listing === null) {
            return [];
        }
        $result = explode($separateur, $listing);
        $result = array_map(function (string $s) {
            return trim($s);
        }, $result);
        return $result;
    }

    public function createCodeFonction(string $codeFonction, array &$warning): ?CodeFonction
    {
        $codeNiveauFonction = substr($codeFonction, 0, 4);
        $fonction_ = $this->getNiveauFonctionService()->getNiveauFonctionByCode($codeNiveauFonction);
        if ($fonction_ === null) {
            $warning[] = "Aucun niveau de fonction connu pour le code " . $codeNiveauFonction . " le code fonction [$codeFonction] n'a pu être créé.";
        }
        $codeFamilleProfessionnelle = substr($codeFonction, 4);
        $famille_ = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelleByCode($codeFamilleProfessionnelle);
        if ($famille_ === null) {
            $warning[] = "Aucune famille professionnelle connue pour le code " . $codeFamilleProfessionnelle . " le code fonction [$codeFonction] n'a pu être créé.";
        }

        if ($fonction_ and $famille_) {
            $code = new CodeFonction();
            $code->setNiveauFonction($fonction_);
            $code->setFamilleProfessionnelle($famille_);
            $code->setCode($code->computeCode());
            return $code;
        }
        return null;
    }
}