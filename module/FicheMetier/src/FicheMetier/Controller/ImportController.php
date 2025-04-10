<?php

namespace FicheMetier\Controller;

use Carriere\Entity\Db\Categorie;
use Carriere\Entity\Db\Correspondance;
use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceElement;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Element\Service\CompetenceReferentiel\CompetenceReferentielServiceAwareTrait;
use FicheMetier\Entity\Db\FicheMetier;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
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
    use CategorieServiceAwareTrait;
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

            //todo fonction !!!
            /** @var FamilleProfessionnelle[] $famillesProfessionnelles */
            $famillesProfessionnelles = [];
            if (in_array("Famille d’activité professionnelle", $header)) {
                $familleDictionnary = [];
                foreach ($data as $item) {
                    $rawFamilleProfessionnelle = $item["Famille d’activité professionnelle"];
                    $familles = explode("|", $rawFamilleProfessionnelle);
                    foreach ($familles as $famille) {
                        $familleDictionnary[$famille] = $famille;
                    }
                }
                foreach ($familleDictionnary as $famille) {
                    $famillesProfessionnelles[$famille] = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelleByLibelle($famille);
                    if ($famillesProfessionnelles[$famille] === null) {
                        $info[] = "Création de la famille professionnelle [" . $famille . "]";
                        $famillesProfessionnelles[$famille] = $this->getFamilleProfessionnelleService()->createWith($famille, ($mode === 'import'));
                    }
                }
            }

            //todo fonction !!!
            /** @var Categorie[] $categories * */
            $categories = [];
            if (in_array("REFERENS_CATEGORIE_EMPLOI", $header)) {
                $categorieDictionnary = [];
                foreach ($data as $item) {
                    $rawCategorie = $item["REFERENS_CATEGORIE_EMPLOI"];
                    $categories = explode("|", $rawCategorie);
                    foreach ($categories as $categorie) {
                        $categorieDictionnary[$categorie] = $categorie;
                    }
                }
                foreach ($categorieDictionnary as $categorie) {
                    $categories[$categorie] = $this->getCategorieService()->getCategorieByLibelle($categorie);
                    if ($categories[$categorie] === null) {
                        $info[] = "Création de la catégorie [" . $categorie . "]";
                        $categories[$categorie] = $this->getCategorieService()->createWith($categorie, ($mode === 'import'));
                    }
                }
            }

            //todo fonction !!!
            /** @var Correspondance[] $correspondances * */
            $correspondances = [];
            if (in_array("Code de la branche d’activité professionnelle", $header)) {
                $correspondanceDictionnary = [];
                foreach ($data as $item) {
                    $correspondance = [];
                    $correspondance["code"] = $item["Code de la branche d’activité professionnelle"] ?? null;
                    $correspondance["intitulé"] = $item["Branche d’activité professionnelle"] ?? null;
                    if ($correspondance["code"]) $correspondanceDictionnary[$correspondance["code"]] = $correspondance;
                }
                foreach ($correspondanceDictionnary as $correspondance) {
                    $correspondances[$correspondance["code"]] = $this->getCorrespondanceService()->getCorrespondanceByTypeAndCode("BAP", $correspondance["code"]);
                    if ($correspondances[$correspondance["code"]] === null) {
                        $info[] = "Création de la correspondance [" . $correspondance["code"] . "|" . $correspondance["intitulé"] . "]";
                        $correspondances[$correspondance["code"]] = $this->getCorrespondanceService()->createWith("BAP", $correspondance["code"], $correspondance["intitulé"], $mode === 'import');
                    }
                }
            }

            //todo fonction !!!
            /** @var Competence[] $competences */
            $competences = [];
            $referentiel = $this->getCompetenceReferentielService()->getCompetenceReferentielByCode("REFERENS");
            if ($referentiel === null) $error[] = "Le référentiel [REFERENS] n'existe pas.";
            if (in_array("COMPETENCES_ID", $header)) {
                foreach ($data as $item) {
                    $ids = explode("|", $item["COMPETENCES_ID"]);
                    foreach ($ids as $id) {
                        $competence = $this->getCompetenceService()->getCompetenceByRefentielAndId($referentiel, $id);
                        if ($competence === null) $warning[] = "La compétence identifié [" . $id . "] n'est pas présente dans le référentiel [REFERENS]";
                        else $competences[$id] = $competence;
                    }
                }
            }


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