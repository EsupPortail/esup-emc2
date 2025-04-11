<?php

namespace FicheMetier\Controller;

use Carriere\Entity\Db\Correspondance;
use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Element\Service\CompetenceReferentiel\CompetenceReferentielServiceAwareTrait;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\Import\ImportServiceAwareTrait;
use FicheReferentiel\Form\Importation\ImportationFormAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Metier\Entity\Db\FamilleProfessionnelle;
use Metier\Entity\Db\Metier;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Metier\Service\Reference\ReferenceServiceAwareTrait;
use Metier\Service\Referentiel\ReferentielServiceAwareTrait;

class ImportController extends AbstractActionController
{
    use ImportServiceAwareTrait;

    use CompetenceServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
    use CompetenceReferentielServiceAwareTrait;
    use CorrespondanceServiceAwareTrait;
    use DomaineServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use FicheMetierServiceAwareTrait;
    use MetierServiceAwareTrait;
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



            $categories = $this->getImportService()->readCategorie($header, $data, $mode, $info, $warning,$error);
            $competences = $this->getImportService()->readCompetence($header, $data, $mode, $info, $warning,$error);
            $correspondances = $this->getImportService()->readCorrespondance($header, $data, $mode, $info, $warning,$error);
            $famillesProfessionnelles = $this->getImportService()->readFamilleProfessionnelle($header, $data, $mode, $info, $warning,$error);

            //todo fonction !!!
            /** @var Metier[] $metiers */
            $metiers = [];
            if (in_array("Code emploi type", $header)) {
                foreach ($data as $item) {
                    $code = $item["Code emploi type"] ?? null;
                    $intitule = $item["Intitulé de l’emploi type"] ?? null;
                    if (!isset($metiers[$code])) {// and !$this->getMetierService()->getMetierByLibelle($intitule)) {
                        $metier = $this->getMetierService()->createWith($intitule, "REFERENS3", $code, null, null, $mode === 'import');
                        if ($item["Famille d’activité professionnelle"]) {
                            $elements = explode("|", $item["Famille d’activité professionnelle"]);
                            foreach ($elements as $element) {
                                $metier->addFamillesProfessionnelles($famillesProfessionnelles[$element]);
                            }
                        }
                        if ($item["Code de la branche d’activité professionnelle"] and $correspondances[$item["Code de la branche d’activité professionnelle"]]) $metier->addCorrespondance($correspondances[$item["Code de la branche d’activité professionnelle"]]);
                        if ($item["REFERENS_CATEGORIE_EMPLOI"] and $categories[$item["REFERENS_CATEGORIE_EMPLOI"]]) $metier->setCategorie($categories[$item["REFERENS_CATEGORIE_EMPLOI"]]);
                        if ($mode === 'import') $this->getMetierService()->update($metier);
                        $metiers[$code] = $metier;
                    }
                }
            }

            /** @var FicheMetier[] $fiches */
            // TODO avoid duplicate !!!
            foreach ($data as $item) {
                $fiche = new FicheMetier();
                $fiche->setMetier($metiers[$item["Code emploi type"]]);
                if ($item["COMPETENCES_ID"]) {
                    $ids = explode('|', $item["COMPETENCES_ID"]);
                    foreach ($ids as $id) {
                        if ($competences[$id]) {
                            $element = new CompetenceElement();
                            $element->setCompetence($competences[$id]);
                            $fiche->addCompetenceElement($element);
                            if ($mode === 'import') {
                                $this->getCompetenceElementService()->create($element);
                                $this->getFicheMetierService()->update($fiche);
                            }
                        }
                    }
                }
                $fiche->setRaw(json_encode($item));
                $fiches[] = $fiche;
            }

//            echo $fiches[0]->getRaw();

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