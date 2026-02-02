<?php

namespace FicheMetier\Controller;

use Application\Provider\Etat\FicheMetierEtats;
use Carriere\Entity\Db\Correspondance;
use Carriere\Entity\Db\FamilleProfessionnelle;
use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Carriere\Service\CorrespondanceType\CorrespondanceTypeServiceAwareTrait;
use Carriere\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Carriere\Service\Niveau\NiveauServiceAwareTrait;
use Carriere\Service\NiveauFonction\NiveauFonctionServiceAwareTrait;
use DateTime;
use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\CompetenceElement;
use Element\Entity\Db\CompetenceType;
use Element\Service\Application\ApplicationServiceAwareTrait;
use Element\Service\ApplicationElement\ApplicationElementServiceAwareTrait;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use Element\Service\NiveauMaitrise\NiveauMaitriseServiceAwareTrait;
use FicheMetier\Entity\Db\ActiviteElement;
use FicheMetier\Entity\Db\CodeFonction;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\MissionElement;
use FicheMetier\Entity\Db\TendanceElement;
use FicheMetier\Entity\Db\TendanceType;
use FicheMetier\Entity\Db\ThematiqueElement;
use FicheMetier\Provider\Parametre\FicheMetierParametres;
use FicheMetier\Service\Activite\ActiviteServiceAwareTrait;
use FicheMetier\Service\ActiviteElement\ActiviteElementServiceAwareTrait;
use FicheMetier\Service\CodeFonction\CodeFonctionServiceAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\Import\ImportServiceAwareTrait;
use FicheMetier\Service\MissionElement\MissionElementServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use FicheMetier\Service\TendanceElement\TendanceElementServiceAwareTrait;
use FicheMetier\Service\TendanceType\TendanceTypeServiceAwareTrait;
use FicheMetier\Service\ThematiqueElement\ThematiqueElementServiceAwareTrait;
use FicheMetier\Service\ThematiqueType\ThematiqueTypeServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Referentiel\Entity\Db\Referentiel;
use Referentiel\Service\Referentiel\ReferentielServiceAwareTrait;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;
use UnicaenParametre\Service\Parametre\ParametreServiceAwareTrait;

class ImportController extends AbstractActionController
{
    use ImportServiceAwareTrait;

    use ActiviteServiceAwareTrait;
    use ActiviteElementServiceAwareTrait;
    use ApplicationServiceAwareTrait;
    use ApplicationElementServiceAwareTrait;
    use CodeFonctionServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;
    use CorrespondanceServiceAwareTrait;
    use CorrespondanceTypeServiceAwareTrait;
    use EtatInstanceServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use MissionElementServiceAwareTrait;
    use NiveauServiceAwareTrait;
    use NiveauFonctionServiceAwareTrait;
    use NiveauMaitriseServiceAwareTrait;
    use ParametreServiceAwareTrait;
    use ReferentielServiceAwareTrait;
    use TendanceElementServiceAwareTrait;
    use TendanceTypeServiceAwareTrait;
    use ThematiqueElementServiceAwareTrait;
    use ThematiqueTypeServiceAwareTrait;


    const FORMAT_RMFP = 'FORMAT_RMFP';
    const FORMAT_REFERENS3 = 'FORMAT_REFERENS3';
    const FORMAT_EMC2 = 'FORMAT_EMC2';

    const FORMATS = [
        self::FORMAT_REFERENS3 => "Format associé à REFERENS3",
        self::FORMAT_RMFP => "Format associé aux RMFP",
        self::FORMAT_EMC2 => "Format interne à EMC2",
    ];

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

    // HEADER EMC2 /////////////////////////////////////////////////////////////////////////////////////////////////////

    const HEADER_EMC2_CODE = "Id";
    const HEADER_EMC2_LIBELLE = "Intitulé de la fiche métier";
    const HEADER_EMC2_RAISON = "Raison d'être";
    const HEADER_EMC2_MISSION = "Missions";
    const HEADER_EMC2_ACTIVITE = "Activités";
    const HEADER_EMC2_NIVEAU_CARRIERE = "Niveau de carrière";
    const HEADER_EMC2_SPECIALITE_TYPE = "Type de la spécialité";
    const HEADER_EMC2_SPECIALITE_LIBELLE = "Libellé de la spécialité";
    const HEADER_EMC2_SPECIALITE_CODE = "Code de la spécialité";
    const HEADER_EMC2_FAMILLE_LIBELLE = "Libellé de la famille professionnelle";
    const HEADER_EMC2_FAMILLE_POSITION = "Position de la famille professionnelle";
    const HEADER_EMC2_APPLICATION = "Applications";
    const HEADER_EMC2_COMPETENCE = "Compétences";


    // HEADER SUPPLEMENTAIRE ///////////////////////////////////////////////////////////////////////////////////////////
    const HEADER_CODE_FONCTION = "Code Fonction";
    const HEADER_CODE_EMPLOITYPE = "Codes Emploi Type";

    public function importAction(): ViewModel
    {
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

            $result = match ($data['format']) {
                ImportController::FORMAT_RMFP => $this->readAsRmfp($data, $filepath, $referentiel, 0),
                ImportController::FORMAT_REFERENS3 => $this->readAsReferens($data, $filepath, $referentiel, 0),
                ImportController::FORMAT_EMC2 => $this->readAsEmc2($data, $filepath, $referentiel, 0),
                default => ['fiches' => [], 'info' => [], 'warning' => [], 'error' => ["Format non reconnu [" . $data['format'] . "]"]],
            };


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

            $referentielId = (isset($data["referentiel"]) and $data["referentiel"] !== "") ? $data["referentiel"] : null;
            $referentiel = $this->getReferentielService()->getReferentiel($referentielId);

            if ($mode === 'import') {
                $info[] = "Importation terminée.";

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
                    $competences = $fiche->getCompetenceCollection()->toArray();
                    $fiche->clearCompetences();
                    $applications = $fiche->getApplicationCollection()->toArray();
                    $fiche->clearApplications();
                    $tendances = $fiche->getTendances();
                    $fiche->clearTendances();
                    $thematiques = $fiche->getThematiques();
                    $fiche->clearThematique();

                    // Il faut clear les elements pour permettre la création
                    $missions = $fiche->getMissions();
                    $fiche->clearMissions();
                    $activites = $fiche->getActivites();
                    $fiche->clearActivites();

                    if ($fiche->getId() === null) $this->getFicheMetierService()->create($fiche);

                    foreach ($competences as $element) {
                        if ($element->getId() === null) $this->getCompetenceElementService()->create($element);
                        else $this->getCompetenceElementService()->update($element);

                        $fiche->addCompetenceElement($element);
                    }
                    foreach ($applications as $element) {
                        if ($element->getId() === null) $this->getApplicationElementService()->create($element);
                        else $this->getApplicationElementService()->update($element);

                        $fiche->addApplicationElement($element);
                    }

                    foreach ($tendances as $tendance) {
                        $tendance->setFicheMetier($fiche);
                        if ($tendance->getId() === null) $this->getTendanceElementService()->create($tendance);
                        else $this->getTendanceElementService()->update($tendance);

                        $fiche->addTendance($tendance);
                    }
                    foreach ($thematiques as $thematique) {
                        $thematique->setFicheMetier($fiche);
                        if ($thematique->getId() === null) $this->getThematiqueElementService()->create($thematique);
                        else $this->getThematiqueElementService()->update($thematique);

                        $fiche->addThematique($thematique);
                    }

                    // GESTION DES MISSIONS ////////////////////////////////////////////////////////////////////////////

                    foreach ($missions as $mission) {
                        if ($mission->getMission()->getId() === null) $this->getMissionPrincipaleService()->create($mission->getMission());
                        else $this->getMissionPrincipaleService()->update($mission->getMission());
                        if ($mission->getId() === null) $this->getMissionElementService()->create($mission);
                        else $this->getMissionElementService()->update($mission);
                        $fiche->addMission($mission);
                    }

                    // GESTION DES ACTIVITÉS ///////////////////////////////////////////////////////////////////////////

                    foreach ($activites as $activite) {
                        if ($activite->getActivite()->getId() === null) $this->getActiviteService()->create($activite->getActivite());
                        else $this->getActiviteService()->update($activite->getActivite());
                        if ($activite->getId() === null) $this->getActiviteElementService()->create($activite);
                        else $this->getActiviteElementService()->update($activite);
                        $fiche->addActivite($activite);
                    }

                    $this->getEtatInstanceService()->setEtatActif($fiche, FicheMetierEtats::ETAT_VALIDE, FicheMetierEtats::TYPE);
                    $this->getFicheMetierService()->update($fiche);
                }
            }
        }

        return new ViewModel([
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
        $colonnesObligatoires = [ImportController::HEADER_REFERENS3_REFERENCE, ImportController::HEADER_REFERENS3_LIBELLE, ImportController::HEADER_REFERENS3_MISSION_LIBELLE];

        $error = [];
        $warning = [];
        $info = [];
        $fiches = [];

        /** Préparation pour le traitement des compétences */
        $dictionnaireCompetence = $this->getCompetenceService()->generateDictionnaire($referentiel, 'reference');
        $tendanceImpact = $this->getTendanceTypeService()->getTendanceTypeByCode(TendanceType::IMPACT);
        $tendanceFacteur = $this->getTendanceTypeService()->getTendanceTypeByCode(TendanceType::FACTEUR);
//        $tendanceCondition = $this->getTendanceTypeService()->getTendanceTypeByCode(TendanceType::CONDITIONS);
        if ($tendanceImpact === null) {
            $warning[] = "Aucune type de tendance [" . TendanceType::IMPACT . "] les informations contenues dans la colonne [Impact sur l'ER] ne seront pas prise en compte";
        }
        if ($tendanceFacteur === null) {
            $warning[] = "Aucune type de tendance [" . TendanceType::FACTEUR . "] les informations contenues dans la colonne [Tendance / évolution] ne seront pas prise en compte";
        }
//        if ($tendanceCondition === null) {
//            $warning[] = "Aucune type de tendance [" . TendanceType::CONDITIONS . "] les informations contenues dans la colonne [Tendance / évolution] ne seront pas prise en compte";
//        }
        $tendancesListing = [
            ['type' => $tendanceFacteur, 'colonne' => self::HEADER_REFERENS3_TENDANCE],
            ['type' => $tendanceImpact, 'colonne' => self::HEADER_REFERENS3_IMPACT],
            //                    ['type' => $tendanceCondition, 'colonne' => self::HEADER_REFERENS3_CONDITION],
        ];

        $debut = (new DateTime())->getTimestamp();

        $csvFile = fopen($filepath, "r");
        // lecture du header + position colonne
        if ($csvFile !== false) {
            $header = fgetcsv($csvFile, null, ";");
            // Remove BOM https://stackoverflow.com/questions/39026992/how-do-i-read-a-utf-csv-file-in-php-with-a-bom
            $header = preg_replace(sprintf('/^%s/', pack('H*', 'EFBBBF')), "", $header);
            $nbElements = count($header);

            foreach ($colonnesObligatoires as $colonne) {
                if (!in_array($colonne, $header)) $error[] = "La colonne obligatoire [" . $colonne . "] est absente.";
            }

            $raws = [];
            while (($row = fgetcsv($csvFile, null, ';')) !== false) {
                $item = [];
                for ($position = 0; $position < $nbElements; ++$position) {
                    $item[$header[$position]] = $row[$position];
                }
                $raws[] = $item;
            }

            if (empty($error)) {
                $dictionnaireNiveauCarriere = $this->getNiveauService()->generateDictionnaire();
                $dictionnaireFamille = $this->getFamilleProfessionnelleService()->generateDictionnaire('libelle');
                $dictionnaireFamilleProfessionnelle = $this->getFamilleProfessionnelleService()->generateDictionnaire('code');
                $dictionnaireSpecialite = $this->getCorrespondanceService()->generateDictionnaire('code');
                $dictionnaireSpecialiteType = $this->getCorrespondanceTypeService()->generateDictionnaire('code');
                $dictionnaireCodeFonction = $this->getCodeFonctionService()->generateDictionnaire();

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

                        $this->readFamilleProfessionnelle($fiche, $raw, ImportController::FORMAT_REFERENS3, $dictionnaireFamille, $dictionnaireSpecialite, $dictionnaireSpecialiteType, $warning);
                        $this->readNiveauCarriere($fiche, $raw[self::HEADER_REFERENS3_CORRESPONDANCE_STATUTAIRE_NIVEAU] ?? "", $dictionnaireNiveauCarriere, $warning);
                        $this->readMissions($fiche, $raw[self::HEADER_REFERENS3_MISSION_LIBELLE] ?? "", "|", $referentiel);
                        $this->readActivites($fiche, $raw[self::HEADER_REFERENS3_MISSION_ACTIVITE] ?? "", "|", $referentiel);
                        $this->readTendancesFromListing($fiche, $raw, $tendancesListing, $warning);
                        $this->readLinks($fiche, $raw[self::HEADER_REFERENS3_URL] ?? "", $raw[self::HEADER_REFERENS3_PDF] ?? "", $warning);
                        $this->readCodeFonction($fiche, $raw[self::HEADER_CODE_FONCTION] ?? "", $dictionnaireCodeFonction, $dictionnaireFamilleProfessionnelle, $warning);
                        $this->readCodesEmploiType($fiche, $raw[ImportController::HEADER_CODE_EMPLOITYPE] ?? "", $warning);

                        /** COMPETENCES ***************************************************************************************/

                        // NB : Le referentiel n'est pas cohérent ; le séparateur de compétence est le '|' ou ','.
                        // '|' est le séparateur que l'on a choisi. On va substituer ',' par '|'
                        $stringCompetenceIds = $raw[self::HEADER_REFERENS3_COMPETENCE] ?? "";
                        $stringCompetenceIds = str_replace(',', '|', $stringCompetenceIds);

                        $comptenceIds = explode("|", $stringCompetenceIds);
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

                        $fiches[] = $fiche;
                    }
                }
            }
        }

        fclose($csvFile);
        $fin = (new DateTime())->getTimestamp();
        $info[] = "Temps de traitement : " . max(1, ($fin - $debut)) . " seconde·s";

        return ['fiches' => $fiches, 'warning' => $warning, 'info' => $info, 'error' => $error];
    }

    public function readAsRmfp($data, string $filepath, Referentiel $referentiel, int $verbosity = 0): array
    {
        $colonnesObligatoires = [ImportController::HEADER_RMFP_REFERENCE, ImportController::HEADER_RMFP_LIBELLE, ImportController::HEADER_RMFP_MISSION_LIBELLE];

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
//        $tendanceCondition = $this->getTendanceTypeService()->getTendanceTypeByCode(TendanceType::CONDITIONS);
        if ($tendanceImpact === null) {
            $warning[] = "Aucune type de tendance [" . TendanceType::IMPACT . "] les informations contenues dans la colonne [Impact sur l'ER] ne seront pas prise en compte";
        }
        if ($tendanceFacteur === null) {
            $warning[] = "Aucune type de tendance [" . TendanceType::FACTEUR . "] les informations contenues dans la colonne [Tendance / évolution] ne seront pas prise en compte";
        }
//        if ($tendanceCondition === null) {
//            $warning[] = "Aucune type de tendance [" . TendanceType::CONDITIONS . "] les informations contenues dans la colonne [Tendance / évolution] ne seront pas prise en compte";
//        }
        $tendancesListing = [
            ['type' => $tendanceFacteur, 'colonne' => self::HEADER_RMFP_TENDANCE],
            ['type' => $tendanceImpact, 'colonne' => self::HEADER_RMFP_IMPACT],
//                    ['type' => $tendanceCondition, 'colonne' => self::HEADER_RMFP_CONDITION],
        ];

        $mode = ($data['mode'] === 'import') ? 'import' : 'preview';
        $debut = (new DateTime())->getTimestamp();


        $csvFile = fopen($filepath, "r");
        // lecture du header + position colonne
        if ($csvFile !== false) {
            $header = fgetcsv($csvFile, null, ";");
            // Remove BOM https://stackoverflow.com/questions/39026992/how-do-i-read-a-utf-csv-file-in-php-with-a-bom
            $header = preg_replace(sprintf('/^%s/', pack('H*', 'EFBBBF')), "", $header);
            $nbElements = count($header);


            foreach ($colonnesObligatoires as $colonne) {
                if (!in_array($colonne, $header)) $error[] = "La colonne obligatoire [" . $colonne . "] est absente.";
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

            if (empty($error)) {
                $dictionnaireFamille = $this->getFamilleProfessionnelleService()->generateDictionnaire('libelle');
                $dictionnaireFamilleProfessionnelle = $this->getFamilleProfessionnelleService()->generateDictionnaire('code');
                $dictionnaireSpecialite = $this->getCorrespondanceService()->generateDictionnaire('code');
                $dictionnaireSpecialiteType = $this->getCorrespondanceTypeService()->generateDictionnaire('code');
                $dictionnaireCodeFonction = $this->getCodeFonctionService()->generateDictionnaire();

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
                        $this->readMissions($fiche, $raw[self::HEADER_RMFP_MISSION_LIBELLE] ?? "", "\n", $referentiel);
                        $this->readActivites($fiche, $raw[self::HEADER_RMFP_MISSION_ACTIVITE] ?? "", "\n", $referentiel);
                        $this->readFamilleProfessionnelle($fiche, $raw, self::FORMAT_RMFP, $dictionnaireFamille, $dictionnaireSpecialite, $dictionnaireSpecialiteType, $warning);
                        $this->readTendancesFromListing($fiche, $raw, $tendancesListing, $warning);
                        $this->readCodeFonction($fiche, $raw[self::HEADER_CODE_FONCTION] ?? "", $dictionnaireCodeFonction, $dictionnaireFamilleProfessionnelle, $warning);
                        $this->readCodesEmploiType($fiche, $raw[ImportController::HEADER_CODE_EMPLOITYPE] ?? "", $warning);

                        /** COMPETENCES ***************************************************************************************/

                        //todo ajouter le type pour éviter les homonymies
                        //todo ajouter un boolean pour la recherche parmi les synonymes

                        $fiche->clearCompetences();
                        $connaissances = self::explodeAndTrim($raw[self::HEADER_RMFP_COMPETENCE_CONNAISSANCE] ?? "", "\n");
                        foreach ($connaissances as $item) {
                            //                    $connaissance = $this->getCompetenceService()->getCompetenceByRefentielAndLibelle($rmfp, $item, $tConnaissance);
                            $connaissance = $dictionnaireConnaissances[$item] ?? null;
                            if ($connaissance !== null) {
                                $element = new CompetenceElement();
                                $element->setCompetence($connaissance);
                                $fiche->addCompetenceElement($element);
                            } else {
                                if ($item !== "") $warning[] = "[" . $item . "] compétence de type [" . $tConnaissance->getLibelle() . "] non trouvée.";
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
                                if ($item !== "") $warning[] = "[" . $item . "] compétence de type [" . $tsavoiretre->getLibelle() . "] non trouvée.";
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
                                if ($item !== "") $warning[] = "[" . $item . "] compétence de type [" . $tsavoirfaire->getLibelle() . "] non trouvée.";
                            }
                        }
                        //                $this->getFicheMetierService()->update($fiche);


                        $fiches[] = $fiche;
                    }

                }
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

    public function readAsEmc2($data, string $filepath, Referentiel $referentiel, int $verbosity = 0): array
    {
        $colonnesObligatoires = [ImportController::HEADER_EMC2_CODE, ImportController::HEADER_EMC2_LIBELLE, ImportController::HEADER_EMC2_MISSION];

        $warning = [];
        $error = [];
        $info = [];
        $fiches = [];

        $csvFile = fopen($filepath, "r");
        // lecture du header + position colonne
        if ($csvFile !== false) {
            $header = fgetcsv($csvFile, null, ";");
            // Remove BOM https://stackoverflow.com/questions/39026992/how-do-i-read-a-utf-csv-file-in-php-with-a-bom
            $header = preg_replace(sprintf('/^%s/', pack('H*', 'EFBBBF')), "", $header);
            $nbElements = count($header);

            foreach ($colonnesObligatoires as $colonne) {
                if (!in_array($colonne, $header)) $error[] = "La colonne obligatoire [" . $colonne . "] est absente.";
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

//            $referentiel = $this->getReferentielService()->getReferentielByLibelleCourt('EMC2');
//            if ($referentiel === null) {
//                $referentiel = new Referentiel();
//                $referentiel->setLibelleCourt('EMC2');
//                $referentiel->setLibelleLong('Référentiel interne à EMC2');
//                $referentiel->setCouleur("#37689B");
//                $this->getReferentielService()->create($referentiel);
//            }

            if (empty($error)) {
                $dictionnaireCodeFonction = $this->getCodeFonctionService()->generateDictionnaire();
                $dictionnaireApplication = $this->getApplicationService()->generateDictionnaire();
                $dictionnaireCompetence = $this->getCompetenceService()->generateCompleteDictionnaire();
                $dictionnaireTendance = $this->getTendanceTypeService()->generateTendanceDictionnaire();
                $dictionnaireThematique = $this->getThematiqueTypeService()->generateThematiqueDictionnaire();
                $dictionnaireNiveauMaitrise = $this->getNiveauMaitriseService()->generateDictionnaire("Contexte et environnement de travail");
                $dictionnaireNiveauCarriere = $this->getNiveauService()->generateDictionnaire('etiquette');
                $dictionnaireFamille = $this->getFamilleProfessionnelleService()->generateDictionnaire('libelle');
                $dictionnaireFamilleProfessionnelle = $this->getFamilleProfessionnelleService()->generateDictionnaire('code');
                $dictionnaireSpecialite = $this->getCorrespondanceService()->generateDictionnaire('code');
                $dictionnaireSpecialiteType = $this->getCorrespondanceTypeService()->generateDictionnaire('code');

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

                        $code = $raw[ImportController::HEADER_EMC2_CODE];
                        $fiche = $this->getFicheMetierService()->getFicheMetierByReferentielAndCode($referentiel, $code);
                        if ($fiche === null) {
                            $fiche = new FicheMetier();
                            $fiche->setReferentiel($referentiel);
                            $fiche->setReference($code);
                        }
                        $fiche->setLibelle($raw[ImportController::HEADER_EMC2_LIBELLE]);

                        $this->readRaison($fiche, $raw[self::HEADER_EMC2_RAISON] ?? "", $warning);
                        $this->readNiveauCarriere($fiche, $raw[self::HEADER_EMC2_NIVEAU_CARRIERE] ?? "", $dictionnaireNiveauCarriere, $warning);
                        $this->readFamilleProfessionnelle($fiche, $raw, ImportController::FORMAT_EMC2, $dictionnaireFamille, $dictionnaireSpecialite, $dictionnaireSpecialiteType, $warning);
                        $this->readMissions($fiche, $raw[self::HEADER_EMC2_MISSION] ?? "", "|", $referentiel);
                        $this->readActivites($fiche, $raw[self::HEADER_EMC2_ACTIVITE] ?? "", "|", $referentiel);
                        $this->readApplications($fiche, $raw[self::HEADER_EMC2_APPLICATION] ?? "", "|", $dictionnaireApplication, $warning);
                        $this->readCompetences($fiche, $raw[self::HEADER_EMC2_COMPETENCE] ?? "", "|", $dictionnaireCompetence, $warning);
                        $this->readTendances($fiche, $raw, $dictionnaireTendance, $warning);
                        $this->readThematiques($fiche, $raw, $dictionnaireThematique, $dictionnaireNiveauMaitrise, $warning);
                        $this->readCodeFonction($fiche, $raw[ImportController::HEADER_CODE_FONCTION] ?? "", $dictionnaireCodeFonction, $dictionnaireFamilleProfessionnelle, $warning);
                        $this->readCodesEmploiType($fiche, $raw[ImportController::HEADER_CODE_EMPLOITYPE] ?? "", $warning);

                        $fiches[] = $fiche;

                    }
                }
            }
        }

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

    public function readRaison(FicheMetier &$fiche, string $data, array &$warning): void
    {
        $fiche->setRaison(trim($data));
    }

    public function readFamilleProfessionnelle(FicheMetier &$fiche, array $raw, string $format, array &$dictionnaireFamille, array &$dictionnaireSpecialite, array &$dictionnaireSpecialiteType, array &$warning): void
    {
        $HEADER_FAMILLE_LIBELLE = match ($format) {
            ImportController::FORMAT_EMC2 => ImportController::HEADER_EMC2_FAMILLE_LIBELLE,
            ImportController::FORMAT_REFERENS3 => ImportController::HEADER_REFERENS3_FAMILLE_LIBELLE,
            ImportController::FORMAT_RMFP => ImportController::HEADER_RMFP_FAMILLE,
            default => null,
        };
        $HEADER_FAMILLE_POSITION = match ($format) {
            ImportController::FORMAT_EMC2 => ImportController::HEADER_EMC2_FAMILLE_POSITION,
            ImportController::FORMAT_REFERENS3 => ImportController::HEADER_REFERENS3_FAMILLE_POSITION,
            default => null,
        };

        $HEADER_SPECIALITE_TYPE = match ($format) {
            ImportController::FORMAT_EMC2 => ImportController::HEADER_EMC2_SPECIALITE_TYPE,
            ImportController::FORMAT_REFERENS3 => "BAP",
            ImportController::FORMAT_RMFP => "DF",
            default => null,
        };
        $HEADER_SPECIALITE_LIBELLE = match ($format) {
            ImportController::FORMAT_EMC2 => ImportController::HEADER_EMC2_SPECIALITE_LIBELLE,
            ImportController::FORMAT_REFERENS3 => ImportController::HEADER_REFERENS3_SPECIALITE_LIBELLE,
            ImportController::FORMAT_RMFP => ImportController::HEADER_RMFP_SPECIALITE,
            default => null,
        };
        $HEADER_SPECIALITE_CODE = match ($format) {
            ImportController::FORMAT_EMC2 => ImportController::HEADER_EMC2_SPECIALITE_CODE,
            ImportController::FORMAT_REFERENS3 => ImportController::HEADER_REFERENS3_SPECIALITE_CODE,
            ImportController::FORMAT_RMFP => ImportController::HEADER_RMFP_SPECIALITE,
            default => null,
        };

        if (isset($raw[$HEADER_FAMILLE_LIBELLE]) and trim($raw[$HEADER_FAMILLE_LIBELLE]) !== '') {
            /** @var FamilleProfessionnelle $famille */
            $famille = $dictionnaireFamille[$raw[$HEADER_FAMILLE_LIBELLE]] ?? null;
            if ($famille === null) {
                $famille = new FamilleProfessionnelle();
                $famille->setLibelle($raw[$HEADER_FAMILLE_LIBELLE]);
                $famille->setPosition((isset($raw[$HEADER_FAMILLE_POSITION]) and $raw[$HEADER_FAMILLE_POSITION] !== "") ? $raw[$HEADER_FAMILLE_POSITION] : null);

                $specialiteType = $raw[$HEADER_SPECIALITE_TYPE] ?? null;
                $specialiteCode = $raw[$HEADER_SPECIALITE_CODE] ?? null;
                $specialiteLibelle = $raw[$HEADER_SPECIALITE_LIBELLE] ?? null;

                $specialite = $dictionnaireSpecialite[$specialiteCode] ?? null;
                if ($specialite === null) {
                    $specialite = new Correspondance();
                    $specialite->setCategorie($specialiteCode);
                    $specialite->setLibelleLong($specialiteLibelle);
                    $specialite->setLibelleCourt($specialiteCode);
                    $specialite->setType($dictionnaireSpecialiteType[$specialiteType] ?? null);
                }
                $famille->setCorrespondance($specialite);
            } else {
                if ($famille->getLibelle() !== $raw[$HEADER_FAMILLE_LIBELLE]) $warning[] = "Le libellé de la famille est différent [FamilleProfessionnelle:" . $famille->getLibelle() . " &ne; Fichier:" . $raw[$HEADER_FAMILLE_LIBELLE] . " ]";
                if ($famille->getPosition() != $raw[$HEADER_FAMILLE_POSITION]) $warning[] = "La position de la famille " . $famille->getLibelle() . " est différente [FamilleProfessionnelle:" . $famille->getPosition() . " &ne; Fichier:" . $raw[$HEADER_FAMILLE_POSITION] . " ]";
                if ($famille->getCorrespondance() !== ($dictionnaireSpecialite[$raw[$HEADER_SPECIALITE_CODE]] ?? null))
                    $warning[] = "La spécialité de la famille " . $famille->getLibelle() . " est différente [Spécialité:" . $famille->getCorrespondance()?->getId() . " &ne; Fichier:" . ($dictionnaireSpecialite[$raw[$HEADER_SPECIALITE_LIBELLE]] ?? null)?->getId() . " ]";
            }
            $fiche->setFamilleProfessionnelle($famille);
        }
    }

    public function readNiveauCarriere(FicheMetier &$fiche, string $data, array &$dictionnaireNiveauCarriere, array &$warning): void
    {
        $niveau = $dictionnaireNiveauCarriere[trim($data)] ?? null;
        if ($niveau === null and trim($data) !== '') $warning[] = "Le niveau de carrière [" . $data . "] est inconnu";
        $fiche->setNiveauCarriere($niveau);

    }

    public function readMissions(FicheMetier &$fiche, ?string $data, string $separator, Referentiel $referentiel): void
    {
        $missions = $fiche->getMissions();
        $fiche->clearMissions();

        if (trim($data) === "") return;
        $readMissions = explode($separator, $data);

        $readMissionPosition = 1;
        foreach ($readMissions as $readMission) {
            $mission = $this->getMissionPrincipaleService()->getMissionPrincipaleByLibelle($readMission);
            if ($mission === null) {
                $reference = $fiche->getReference() . "_" . $readMissionPosition;
                $mission = $this->getMissionPrincipaleService()->createWith($readMission, $referentiel, $reference, false);
            }
            $missionElement = $this->getMissionElementService()->getMissionElementFromArray($missions, $mission);
            if ($missionElement === null) {
                $missionElement = new MissionElement();
                $missionElement->setMission($mission);
            }
            $missionElement->setPosition($readMissionPosition);
            $fiche->addMission($missionElement);
            $readMissionPosition++;
        }
    }

    public function readActivites(FicheMetier &$fiche, string $data, string $separator, Referentiel $referentiel): void
    {
        $activites = $fiche->getActivites();
        $fiche->clearActivites();

        if (trim($data) === "") return;
        $readActivites = explode($separator, $data);

        $readActivitePosition = 1;
        foreach ($readActivites as $readActivite) {
            $activite = $this->getActiviteService()->getActiviteByLibelle($readActivite);
            if ($activite === null) {
                $reference = $fiche->getReference() . "_" . $readActivitePosition;
                $activite = $this->getActiviteService()->createWith($readActivite, $referentiel, $reference, false);
            }
            $activiteElement = $this->getActiviteElementService()->getActiviteElementFromArray($activites, $activite);
            if ($activiteElement === null) {
                $activiteElement = new ActiviteElement();
                $activiteElement->setActivite($activite);
            }
            $activiteElement->setPosition($readActivitePosition);
            $fiche->addActivite($activiteElement);
            $readActivitePosition++;
        }
    }

    public function readCodesEmploiType(FicheMetier &$fiche, string $data, array &$warning): void
    {
        if (trim($data) != '') {
            $fiche->setCodesEmploiType(trim(trim($data)));
        }
    }

    public function readCodeFonction(FicheMetier &$fiche, ?string $data, array &$dictionnaireCodeFonction, array &$dictionnaireFamilleProfessionnelle, array &$warning): void
    {
        $code = $fiche->getFamilleProfessionnelle()?->computeCode();
        if ($code and !isset($dictionnaireFamilleProfessionnelle[$code])) $dictionnaireFamilleProfessionnelle[$code] = $fiche->getFamilleProfessionnelle();
        $codeFonction = (isset($data) and trim($data) !== '') ? trim($data) : null;
        if ($codeFonction !== null) {
            if (isset($dictionnaireCodeFonction[$codeFonction])) {
                $fiche->setCodeFonction($dictionnaireCodeFonction[$codeFonction]);
            } else {
                $code = $this->createCodeFonction($codeFonction, $dictionnaireFamilleProfessionnelle, $warning);
                $fiche->setCodeFonction($code);
            }
            if ($fiche->getCodeFonction()) $fiche->setFamilleProfessionnelle($fiche->getCodeFonction()->getFamilleProfessionnelle());
        }
    }

    public function createCodeFonction(string $codeFonction, array &$dictionnaireFamille, array &$warning): ?CodeFonction
    {
        $codeNiveauFonction = substr($codeFonction, 0, 4);
        $fonction_ = $this->getNiveauFonctionService()->getNiveauFonctionByCode($codeNiveauFonction);
        if ($fonction_ === null) {
            $warning[] = "Aucun niveau de fonction connu pour le code " . $codeNiveauFonction . " le code fonction [$codeFonction] n'a pu être créé.";
        }
        $codeFamilleProfessionnelle = substr($codeFonction, 4);
        $famille_ = $dictionnaireFamille[$codeFamilleProfessionnelle] ?? null;
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

    public function readApplications(FicheMetier &$fiche, string $data, string $separator, array &$dictionnaireApplication, array &$warning): void
    {
        if (trim($data) === "") return;

        $applicationIds = explode($separator, $data);
        $applications = $fiche->getApplicationListe();
        foreach ($applications as $application) {
            $dictionnaireFicheMetierApplication[$application->getApplication()->getId()] = $application;
        }

        $fiche->clearApplications();
        foreach ($applicationIds as $applicationId) {
            if (isset($dictionnaireFicheMetierApplication[$applicationId])) {
                $fiche->addApplicationElement($dictionnaireFicheMetierApplication[$applicationId]);
            } else {
                $application = $dictionnaireApplication[$applicationId] ?? null;
                if ($application !== null) {
                    $element = new ApplicationElement();
                    $element->setApplication($application);
                    $fiche->addApplicationElement($element);
                } else {
                    $warning[] = "[" . $applicationId . "] application non trouvée.";
                }
            }
        }
    }

    // pour le moment cela ne sera compatible qu'avec le format EMC2 (les autres formats sont particuliers (eventuellement referens3)
    public function readCompetences(FicheMetier &$fiche, string $data, string $separator, array &$dictionnaireCompetence, array &$warning): void
    {
        if (trim($data) === "") return;

        $competenceIds = explode($separator, $data);
        $dictionnaireFicheMetierCompetence = [];
        $competences = $fiche->getCompetenceListe();
        foreach ($competences as $competence) {
            $dictionnaireFicheMetierCompetence[$competence->getCompetence()->getId()] = $competence;
        }
        $fiche->clearCompetences();
        foreach ($competenceIds as $competenceId) {
            $id = trim($competenceId);
            if (isset($dictionnaireFicheMetierCompetence[$id])) {
                $fiche->addCompetenceElement($dictionnaireFicheMetierCompetence[$id]);
            } else {
                $competence = $dictionnaireCompetence[$id] ?? null;
                if ($competence !== null) {
                    $element = new CompetenceElement();
                    $element->setCompetence($competence);
                    $fiche->addCompetenceElement($element);
                } else {
                    $warning[] = "[" . $id . "] compétence non trouvée.";
                }
            }
        }
    }

    public function readTendances(FicheMetier &$fiche, array $raw, array &$dictionnaireTendance, array &$warning): void
    {
        $tendances = $fiche->getTendances();
        foreach ($tendances as $tendance) {
            $dictionnaireFicheMetierTendance[$tendance->getType()->getLibelle()] = $tendance;
        }
        foreach ($dictionnaireTendance as $libelle => $type) {
            if (isset($raw[$libelle]) and trim($raw[$libelle]) !== "") {
                if (isset($dictionnaireFicheMetierTendance[$libelle])) {
                    $element = $dictionnaireFicheMetierTendance[$libelle];
                } else {
                    $type = $dictionnaireTendance[$libelle];
                    $element = new TendanceElement();
                    $element->setType($type);
                }
                $element->setTexte($raw[$libelle]);
                $fiche->addTendance($element);
            }
        }
    }

    public function readThematiques(FicheMetier &$fiche, array $raw, array &$dictionnaireThematique, array &$dictionnaireNiveau, array &$warning): void
    {
        $thematiques = $fiche->getThematiques();
        $fiche->clearTendances();
        foreach ($thematiques as $thematique) {
            $dictionnaireFicheMetierThematique[$thematique->getType()->getLibelle()] = $thematique;
        }
        foreach ($dictionnaireThematique as $libelle => $type) {
            if (isset($raw[$libelle]) and trim($raw[$libelle]) !== "") {
                if (isset($dictionnaireFicheMetierThematique[$libelle])) {
                    $element = $dictionnaireFicheMetierThematique[$libelle];
                } else {
                    $type = $dictionnaireThematique[$libelle];
                    $element = new ThematiqueElement();
                    $element->setType($type);
                }
                $niveau = $dictionnaireNiveau[$raw[$libelle]]??null;
                if ($raw[$libelle] !== "" and $niveau === null) {
                    $warning[] = "Le niveau de Contexte [" . $raw[$libelle] . "] n'existe pas.";
                }
//                if ($niveau !== null) {
                    $element->setNiveauMaitrise($niveau);
                    $fiche->addThematique($element);
//                }
            }
        }
    }

    public function readTendancesFromListing(FicheMetier &$fiche, array $raw, array &$tendancesListing, array &$warning): void
    {
        $tendances = $fiche->getTendances();
        $fiche->clearTendances();
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
                }
                $fiche->addTendance($tendance);
            }
        }
    }


    public function readLinks(FicheMetier &$fiche, string $urlLink, string $pdfLink, array &$warning): void
    {
        $fiche->setLienWeb(trim($urlLink) !== '' ? trim($urlLink) : null);
        $fiche->setLienPdf(trim($pdfLink) !== '' ? trim($pdfLink) : null);
    }
}