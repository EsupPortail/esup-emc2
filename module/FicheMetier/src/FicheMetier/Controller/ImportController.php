<?php

namespace FicheMetier\Controller;

use Application\Provider\Etat\FicheMetierEtats;
use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use DateTime;
use Element\Entity\Db\CompetenceElement;
use Element\Entity\Db\CompetenceType;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Element\Service\CompetenceReferentiel\CompetenceReferentielServiceAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\FicheMetierMission;
use FicheMetier\Entity\Db\Mission;
use FicheMetier\Entity\Db\MissionActivite;
use FicheMetier\Form\FicheMetierImportation\FicheMetierImportationFormAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\FicheMetierMission\FicheMetierMissionServiceAwareTrait;
use FicheMetier\Service\Import\ImportServiceAwareTrait;
use FicheMetier\Service\MissionActivite\MissionActiviteServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Metier\Entity\Db\FamilleProfessionnelle;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Metier\Service\Reference\ReferenceServiceAwareTrait;
use Metier\Service\Referentiel\ReferentielServiceAwareTrait;
use UnicaenEtat\Service\EtatInstance\EtatInstanceServiceAwareTrait;

class ImportController extends AbstractActionController
{
    use ImportServiceAwareTrait;

    use CompetenceServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use CompetenceReferentielServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;
    use CorrespondanceServiceAwareTrait;
    use EtatInstanceServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use FicheMetierMissionServiceAwareTrait;
    use MetierServiceAwareTrait;
    use MissionActiviteServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use ReferentielServiceAwareTrait;
    use ReferenceServiceAwareTrait;

    use FicheMetierImportationFormAwareTrait;

    const FORMAT_RMFP = 'FORMAT_RMFP';
    const FORMAT_REFERENS3 = 'FORMAT_REFERENS3';

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
            $mode = ($data['mode'] === 'import')?'import':'preview';
            $files = $request->getFiles()->toArray();
            $file = !empty($files)?current($files):null;

            $filename = $data['filename']??null;
            $filepath = $data['filepath']??null;

            if (($file === null OR $file['tmp_name'] === "") AND $filepath === null) {
                $error[] = "Aucun fichier fourni";
            } else {
                if ($filepath === null) {
                    $filepath = '/tmp/import_fichemetier_' . (new DateTime())->getTimestamp() . '.csv';
                    $filename = $file['name'];
                    copy($file['tmp_name'], $filepath);
                }
            }

            if ($data['format'] === ImportController::FORMAT_RMFP) {
                $result = $this->readAsRmfp($data, $filepath,0);
                /** @var FicheMetier[] $fiches */
                $fiches = $result["fiches"];

                $referentielId = (isset($data["referentiel"]) AND $data["referentiel"] !== "") ? $data["referentiel"] : null;
                $referentiel = $this->getReferentielService()->getReferentiel($referentielId);

                if ($mode === 'import') {
                    // recuperation des nouvelles familles professionnelles
                    foreach ($fiches as $fiche) {
                        if ($fiche->getFamilleProfessionnelle() AND $fiche->getFamilleProfessionnelle()->getId() === null) {
                            $this->getFamilleProfessionnelleService()->create($fiche->getFamilleProfessionnelle());
                        }
                    }
                    $missions = [];
                    $elements = [];
                    foreach ($fiches as $fiche) {
                        foreach ($fiche->getMissions() as $mission) {
                            $missions[] = $mission;
                        }
                        $fiche->clearMissions();
                        foreach ($fiche->getCompetenceCollection() as $competence) {
                            $elements[] = $competence;
                        }
                        $fiche->clearCompetences();

                        //todo attention au update
                        $this->getFicheMetierService()->create($fiche);

                        foreach ($missions as $mission) {
                            $this->getFicheMetierMissionService()->deepCreate($mission);
                            $fiche->addMission($mission);
                        }
                        $this->getFicheMetierService()->update($fiche);
                        foreach ($elements as $element) {
                            $this->getCompetenceElementService()->create($element);
                            $fiche->addCompetenceElement($element);
                        }

                        $this->getEtatInstanceService()->setEtatActif($fiche, FicheMetierEtats::ETAT_VALIDE, FicheMetierEtats::TYPE);
                        $this->getFicheMetierService()->update($fiche);
                    }
                    //
                }
//                $vm = new ViewModel([
//                    'warning' => $result['warning'],
//                    'info' => $result['info'],
//                    'fiches' => $fiches,
//                ]);
////                $vm->setTemplate('fiche-metier/import/temporaire_rmfp');
//                return $vm;
            }

            if ($data['format'] === ImportController::FORMAT_REFERENS3) {

                $referentielId = (isset($data["referentiel"]) AND $data["referentiel"] !== "") ? $data["referentiel"] : null;
                $referentiel = $this->getReferentielService()->getReferentiel($referentielId);
                $mode = (isset($data["mode"]) AND $data["mode"] !== "") ? $data["mode"] : null;

                if ($referentiel !== null AND $mode !== null AND $filepath !== "") {
                    $header = [];
                    $data = [];
                    // Lecture du fichier de REFERENS3
                    $csvFile = fopen($filepath, "r");
                    if ($csvFile !== false) {
                        $header = fgetcsv($csvFile, null, ";");
                        // Remove BOM https://stackoverflow.com/questions/39026992/how-do-i-read-a-utf-csv-file-in-php-with-a-bom
                        $header = preg_replace(sprintf('/^%s/', pack('H*','EFBBBF')), "", $header);
                        $nbElements = count($header);

                        while (($row = fgetcsv($csvFile, null, ';')) !== false) {
                            $item = [];
                            for ($position = 0; $position < $nbElements; ++$position) {
                                $item[$header[$position]] = $row[$position];
                            }
                            $data[] = $item;
                        }
                        fclose($csvFile);
                    }

                    $ok = true;
                    if (!in_array("Code emploi type", $header)) {
                        $ok = false;
                        $error[] = "La colonne obligatoire <code>Code emploi type</code> est absente";
                    }
                    if (!in_array("Intitulé de l’emploi type", $header)) {
                        $ok = false;
                        $error[] = "La colonne obligatoire <code>Intitulé de l’emploi type</code> est absente";
                    }

                    if ($ok) {
                        $categories = $this->getImportService()->readCategorie($header, $data, $mode, $info, $warning, $error);
                        $competences = $this->getImportService()->readCompetence($header, $data, $mode, $info, $warning, $error);
                        $correspondances = $this->getImportService()->readCorrespondance($header, $data, $mode, $info, $warning, $error);
                        $famillesProfessionnelles = $this->getImportService()->readFamilleProfessionnelle($header, $data, $mode, $info, $warning, $error);
                        $metiers = $this->getImportService()->readMetier($header, $data, $mode, $famillesProfessionnelles, $correspondances, $categories, $referentiel, $info, $warning, $error);


                        /** @var FicheMetier[] $fiches */
                        foreach ($data as $item) {
                            $raw = json_encode($item);
                            $existingFiches = ($mode === 'import') ? $this->getFicheMetierService()->getFichesMetiersByMetier($metiers[$item["Code emploi type"]], $raw) : [];
                            if (!empty($existingFiches)) {
                                $warning[] = "Une fiche de métier pour métier [" . $item["Code emploi type"] . "|" . $item["Intitulé de l’emploi type"] . "] existe déjà avec les mêmes données sources.";
                            } else {
                                $fiche = new FicheMetier();
                                $fiche->setRaw($raw);
                                $fiche->setMetier($metiers[$item["Code emploi type"]]);
                                if ($mode === 'import') $this->getFicheMetierService()->create($fiche);
                                if (isset($item["COMPETENCES_ID"]) and $item["COMPETENCES_ID"] !== '') {
                                    $ids = explode('|', $item["COMPETENCES_ID"]);
                                    foreach ($ids as $id) {
                                        if (isset($competences[$id])) {
                                            $element = new CompetenceElement();
                                            $element->setCompetence($competences[$id]);
                                            $fiche->addCompetenceElement($element);
                                            if ($mode === 'import') {
                                                $this->getCompetenceElementService()->create($element);
                                            }
                                        }
                                    }
                                }
                                //Mission et activité
                                if (isset($item["Mission"]) and $item["Mission"] !== '') {
                                    $intitule = $item["Mission"];
                                    $activites = [];
                                    if (isset($item["Activités principales"])) {
                                        $activites = explode("|", $item["Activités principales"]);
                                    }
                                    $mission = $this->getMissionPrincipaleService()->createWith($intitule, $activites, $mode === 'import');


                                    $ficheMetierMission = new FicheMetierMission();
                                    $ficheMetierMission->setMission($mission);
                                    $ficheMetierMission->setFicheMetier($fiche);
                                    $ficheMetierMission->setOrdre(1);

                                    if ($mode === 'import') {
                                        $this->getFicheMetierMissionService()->create($ficheMetierMission);
                                        $this->getEtatInstanceService()->setEtatActif($fiche, FicheMetierEtats::ETAT_VALIDE, FicheMetierEtats::TYPE);
                                    } else {
                                        $fiche->addMission($ficheMetierMission);
                                    }


                                }

                                if ($mode === 'import') {
                                    $this->getFicheMetierService()->update($fiche);
                                }

                                //if ($mode === 'import') {
                                //print((++$position) . "/" . count($data) . $fiche->getMetier()->getLibelle() . "<br>");
                                //flush();
                                //}
                                $fiches[] = $fiche;
                            }
                        }
                    }
                }
            }
        }

        if ($referentiel === null) $error[] = "Aucun référentiel de sélectionné";
        else $form->get('referentiel')->setValue($referentiel->getId());
        if ($mode === null) $error[] = "Aucun mode d'importation de sélectionné";
        else $form->get('mode')->setValue($mode);
        if ($fichier_path === "") $error[] = "Aucun fichier fourni";
        else {
            $form->get('fichier')->setValue($data['fichier']??null);
        }

        return new ViewModel([
            'form' => $form,
            'info' => $info,
            'warning' => $warning,
            'error' => $error,
            'fiches' => $fiches,

            'referentiels' => $this->getReferentielService()->getReferentiels(),
            'data' => $data,
            'file' => $file,
            'filepath' => $filepath??null,
            'filename' => $filename??null,
        ]);
    }

    public function readAsReferens($data, array $file, int $verbosity = 0) : array
    {
        return ['error' => ["Not yep re-implemented"]];
    }

    public function readAsRmfp($data, string $filepath, int $verbosity = 0) : array
    {
        /** Préparation pour le traitement des compétences */
        $rmfp = $this->getCompetenceReferentielService()->getCompetenceReferentielByCode('RMFP');
        $tConnaissance = $this->getCompetenceTypeService()->getCompetenceTypeByCode(CompetenceType::CODE_CONNAISSANCE);
        $tsavoiretre = $this->getCompetenceTypeService()->getCompetenceTypeByCode(CompetenceType::CODE_COMPORTEMENTALE);
        $tsavoirfaire = $this->getCompetenceTypeService()->getCompetenceTypeByCode(CompetenceType::CODE_OPERATIONNELLE);

        $dictionnaireConnaissances = $this->getCompetenceService()->generateDictionnaire($rmfp, $tConnaissance);
        $dictionnaireSavoirFaire = $this->getCompetenceService()->generateDictionnaire($rmfp, $tsavoirfaire);
        $dictionnaireSavoirEtre = $this->getCompetenceService()->generateDictionnaire($rmfp, $tsavoiretre);

        $mode = ($data['mode'] === 'import')?'import':'preview';
        $info[] = "Mode activé [".$mode."]";

        $debut = (new DateTime())->getTimestamp();

        $warning = [];

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

            $positionCode       = array_search("Code", $header);
            $positionIntitule   = array_search("Intitulé", $header);
            $positionDefinition = array_search("Définition synthétique de l'ER", $header);
            $positionFamille    = array_search("Famille", $header);


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
                $intitule = $raw["Intitulé"];
                $code = $raw["Code"];
                $fiche = new FicheMetier();
                $fiche->setRaw(json_encode($raw));
                $fiche->setLibelle($intitule);
                $fiche->setCode($code);
//                $this->getFicheMetierService()->create($fiche);

                $famille = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelleByLibelle(trim($raw["Famille"]));
                if ($famille === null) {
                    $famille = new FamilleProfessionnelle();
                    $famille->setLibelle(trim($raw["Famille"]));

                }
                $fiche->setFamilleProfessionnelle($famille);

                /** Partie Mission et activités ***********************************************************************/

                // !! quid !!
                // > revoir un peu la fiche pour avoir une définition + une liste d'activité sans mission ?
                // > fiche metier devrait pouvoir avoir des activités hors mission ?

                $missionLibelle = $raw["Définition synthétique de l'ER"];
                $activites = explode("\n",$raw["Activités de l'ER"]);





                $mission = $this->getMissionPrincipaleService()->getMissionPrincipaleByLibelle($missionLibelle);
                if ($mission === null) {
                    $mission = new Mission();
                    $mission->setLibelle($missionLibelle);
//                    $this->getMissionPrincipaleService()->create($mission);
                    // todo set code

                    $position = 1;
                    foreach ($activites as $activiteLibelle) {
                        $activite = new MissionActivite();
                        $activite->setLibelle($activiteLibelle);
                        $activite->setOrdre($position++);
                        $activite->setMission($mission);
//                        $this->getMissionActiviteService()->create($activite);
                        $mission->addMissionActivite($activite);
                    }
                }

                $ficheMission = new FicheMetierMission();
                $ficheMission->setMission($mission);
                $ficheMission->setFicheMetier($fiche);
                $ficheMission->setOrdre(1);
//                    $this->getFicheMetierMissionService()->create($ficheMission);
                $fiche->addMission($ficheMission);


                /** COMPETENCES ***************************************************************************************/



                //todo ajouter le type pour eviter les homonymies
                //todo ajouter un boolean pour la recherche parmi les synonymes

                $connaissances = self::explodeAndTrim($raw["Connaissances"], "\n");
                foreach ($connaissances as $item) {
//                    $connaissance = $this->getCompetenceService()->getCompetenceByRefentielAndLibelle($rmfp, $item, $tConnaissance);
                    $connaissance = $dictionnaireConnaissances[$item]??null;
                    if ($connaissance !== null) {
                        $element = new CompetenceElement();
                        $element->setCompetence($connaissance);
                        $fiche->addCompetenceElement($element);
                    } else {
                        $warning[] = "[".$item."] compétence de type [".$tConnaissance->getLibelle()."] non trouvée.";
                    }
                }
                $savoiretres = self::explodeAndTrim($raw["Savoir-être"], "\n");
                foreach ($savoiretres as $item) {
//                    $savoiretre = $this->getCompetenceService()->getCompetenceByRefentielAndLibelle($rmfp, $item,$tsavoiretre);
                    $savoiretre = $dictionnaireSavoirEtre[$item]??null;
                    if ($savoiretre !== null) {
                        $element = new CompetenceElement();
                        $element->setCompetence($savoiretre);
//                        $this->getCompetenceElementService()->create($element);
                        $fiche->addCompetenceElement($element);
                    } else {
                        $warning[] = "[".$item."] compétence de type [".$tsavoiretre->getLibelle()."] non trouvée.";
                    }
                }
                $savoirfaires = self::explodeAndTrim($raw["Savoir-faire"], "\n");
                foreach ($savoirfaires as $item) {
//                    $savoirfaire = $this->getCompetenceService()->getCompetenceByRefentielAndLibelle($rmfp, $item, $tsavoirfaire);
                    $savoirfaire = $dictionnaireSavoirFaire[$item]??null;
                    if ($savoirfaire !== null) {
                        $element = new CompetenceElement();
                        $element->setCompetence($savoirfaire);
//                        $this->getCompetenceElementService()->create($element);
                        $fiche->addCompetenceElement($element);
                    } else {
                        $warning[] = "[".$item."] compétence de type [".$tsavoirfaire->getLibelle()."] non trouvée.";
                    }
                }
//                $this->getFicheMetierService()->update($fiche);



                $fiches[] = $fiche;

            }

        }
        fclose($csvFile);
        $fin = (new DateTime())->getTimestamp();
        $info[] = "Temps de traitement : " . max(1,($fin-$debut)) . " seconde·s";

        return ['fiches' => $fiches, 'warning' => $warning, 'info' => $info];
    }

    /** @return $string */
    static public function explodeAndTrim(?string $listing, string $separateur = "\n"): array
    {
        if ($listing === null) { return []; }
        $result = explode($separateur, $listing);
        $result = array_map(function(string $s) { return trim($s); }, $result);
        return $result;
    }
}