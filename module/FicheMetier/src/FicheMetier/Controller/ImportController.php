<?php

namespace FicheMetier\Controller;

use Application\Provider\Etat\FicheMetierEtats;
use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Element\Entity\Db\CompetenceElement;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Element\Service\CompetenceReferentiel\CompetenceReferentielServiceAwareTrait;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Entity\Db\FicheMetierMission;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\FicheMetierMission\FicheMetierMissionServiceAwareTrait;
use FicheMetier\Service\Import\ImportServiceAwareTrait;
use FicheMetier\Service\MissionPrincipale\MissionPrincipaleServiceAwareTrait;
use FicheReferentiel\Form\Importation\ImportationFormAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
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
    use CorrespondanceServiceAwareTrait;
    use EtatInstanceServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use FicheMetierMissionServiceAwareTrait;
    use MetierServiceAwareTrait;
    use MissionPrincipaleServiceAwareTrait;
    use ReferentielServiceAwareTrait;
    use ReferenceServiceAwareTrait;

    use ImportationFormAwareTrait;

    public function importAction(): ViewModel
    {
        $form = $this->getImportationForm();
        $form->setAttribute("action", $this->url()->fromRoute("fiche-metier/import"));

        $fiches = [];
        $info = [];
        $warning = [];
        $error = [];

        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $request->getPost();
            $file = $request->getFiles();

            $fichier_path = $file['fichier']['tmp_name'];
            $mode = $data['mode'];


            $referentiel = $this->getReferentielService()->getReferentielByCode("REFERENS3");
            if ($referentiel === null) {
                $error[] = "Le référentiel [REFERENS3] n'est pas présent dans l'application";
            }

            $header = [];
            $data = [];
            // Lecture du fichier de REFERENS3
            $csvFile = fopen($fichier_path, "r");
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


            $categories = $this->getImportService()->readCategorie($header, $data, $mode, $info, $warning, $error);
            $competences = $this->getImportService()->readCompetence($header, $data, $mode, $info, $warning, $error);
            $correspondances = $this->getImportService()->readCorrespondance($header, $data, $mode, $info, $warning, $error);
            $famillesProfessionnelles = $this->getImportService()->readFamilleProfessionnelle($header, $data, $mode, $info, $warning, $error);
            $metiers = $this->getImportService()->readMetier($header, $data, $mode, $famillesProfessionnelles, $correspondances, $categories, $info, $warning, $error);


            /** @var FicheMetier[] $fiches */
            foreach ($data as $item) {
                $raw = json_encode($item);
                $existingFiches = ($mode === 'import') ? $this->getFicheMetierService()->getFichesMetiersByMetier($metiers[$item["Code emploi type"]], $raw) : [];
                if (!empty($existingFiches)) {
                    $warning[] = "Une fiche de métier pour métier [".$item["Code emploi type"]."|".$item["Intitulé de l’emploi type"]."] existe déjà avec les mêmes données sources.";
                } else {
                    $fiche = new FicheMetier();
                    $fiche->setRaw($raw);
                    $fiche->setMetier($metiers[$item["Code emploi type"]]);
                    if ($mode === 'import') $this->getFicheMetierService()->create($fiche);
                    if ($item["COMPETENCES_ID"]) {
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
                    if (isset($item["Mission"])) {
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

                    if ($mode === 'import') { $this->getFicheMetierService()->update($fiche); }

                    //if ($mode === 'import') {
                        //print((++$position) . "/" . count($data) . $fiche->getMetier()->getLibelle() . "<br>");
                        //flush();
                    //}
                    $fiches[] = $fiche;
                }
            }
        }

        return new ViewModel([
            'form' => $form,
            'info' => $info,
            'warning' => $warning,
            'error' => $error,
            'fiches' => $fiches,
        ]);
    }

}