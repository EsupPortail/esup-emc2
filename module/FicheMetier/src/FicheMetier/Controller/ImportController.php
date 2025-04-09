<?php

namespace FicheMetier\Controller;

use Carriere\Service\Categorie\CategorieServiceAwareTrait;
use Carriere\Service\Correspondance\CorrespondanceServiceAwareTrait;
use FicheReferentiel\Form\Importation\ImportationFormAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Metier\Service\Domaine\DomaineServiceAwareTrait;
use Metier\Service\FamilleProfessionnelle\FamilleProfessionnelleServiceAwareTrait;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Metier\Service\Reference\ReferenceServiceAwareTrait;
use Metier\Service\Referentiel\ReferentielServiceAwareTrait;

class ImportController extends AbstractActionController
{
    use CategorieServiceAwareTrait;
    use CorrespondanceServiceAwareTrait;
    use DomaineServiceAwareTrait;
    use FamilleProfessionnelleServiceAwareTrait;
    use MetierServiceAwareTrait;
    use ReferentielServiceAwareTrait;
    use ReferenceServiceAwareTrait;

    use ImportationFormAwareTrait;

    public function importAction(): ViewModel
    {
        $form = $this->getImportationForm();
        $form->setAttribute("action", $this->url()->fromRoute("fiche-metier/import"));


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

            $csvFile = fopen($fichier_path, "r"); // Ouvrir le fichier en mode lecture


            if ($csvFile !== false) {
                $header = fgetcsv($csvFile, null, ";");

                $colonneToMatch = [
                    "Code de la branche d’activité professionnelle",
                    "Branche d’activité professionnelle",
                    "Famille d’activité professionnelle",
                    "Intitulé de l’emploi type",
                    "Code emploi type",
                    "REFERENS_CATEGORIE_EMPLOI",
                    "Choucroute !!!",
                ];
                $positions = [];
                $familleDictionnary = [];
                $categorieDictionnary = [];
                $correspondanceDictionnary = [];
                $metiers = [];
                foreach ($header as $key => $value) {
                    if (in_array($value, $colonneToMatch)) {
                        $positions[$value] = $key;
                    }
                }
                foreach ($colonneToMatch as $colonne) {
                    if (!isset($positions[$colonne])) $warning[] = "Échec de la détection de la colonne [" . $colonne . "]";
                }

                $metier_familles = [];
                $metier_categories = [];
                while (($row = fgetcsv($csvFile, null, ';')) !== false) {
                    $familles = null;
                    if (isset($positions["Famille d’activité professionnelle"])) {
                        $famille_ = $row[$positions["Famille d’activité professionnelle"]];
                        $familles = explode("|", $famille_);
                        foreach ($familles as $famille) $familleDictionnary[$famille] = $famille;
                    }
                    $categorie = null;
                    if (isset($positions["REFERENS_CATEGORIE_EMPLOI"])) {
                        $categorie = $row[$positions["REFERENS_CATEGORIE_EMPLOI"]];
                        $categorieDictionnary[$categorie] = $categorie;
                    }
                    $correspondance = null;
                    if (isset($positions["Code de la branche d’activité professionnelle"])) {
                        $correspondance["code"] = $row[$positions["Code de la branche d’activité professionnelle"]];
                        if (isset($positions["Branche d’activité professionnelle"])) $correspondance["intitulé"] = $row[$positions["Branche d’activité professionnelle"]];
                        $correspondanceDictionnary[$correspondance["code"]] = $correspondance;
                    }
                    $intitule = null;
                    if (isset($positions["Intitulé de l’emploi type"])) {
                        $intitule = $row[$positions["Intitulé de l’emploi type"]];
                        $code = null;
                        if (isset($positions["Code emploi type"])) {
                            $code = $row[$positions["Code emploi type"]];
                        }
                        $metiers[] = ["intitulé" => $intitule, "code" => $code];
                    }
                    if ($intitule and $familles) {
                        $metier_familles[$intitule] = $familles;
                    }
                    if ($intitule and $categorie) {
                        $metier_categories[$intitule] = $categorie;
                    }
                    if ($intitule and $correspondance) {
                        $metier_correspondance[$intitule] = $correspondance;
                    }
                }
                fclose($csvFile); // Fermer le fichier

                $famillesObjects = [];
                foreach ($familleDictionnary as $famille) {
                    $famillesObjects[$famille] = $this->getFamilleProfessionnelleService()->getFamilleProfessionnelleByLibelle($famille);

                    if ($famillesObjects[$famille] === null) {
                        $info[] = "Création de la famille professionnelle [" . $famille . "]";
                        $famillesObjects[$famille] = $this->getFamilleProfessionnelleService()->createWith($famille, ($mode === 'import'));
                    }
                }
                $categoriesObjects = [];
                foreach ($categorieDictionnary as $categorie) {
                    $categoriesObjects[$categorie] = $this->getCategorieService()->getCategorieByLibelle($categorie);;
                    if ($categoriesObjects[$categorie] === null) {
                        $info[] = "Création de la catégorie [" . $categorie . "]";
                        $categoriesObjects[$categorie] = $this->getCategorieService()->createWith($categorie, ($mode === 'import'));

                    }
                }

                $correspondanceObjects = [];
                foreach ($correspondanceDictionnary as $correspondance) {
                    $correspondanceObjects[$correspondance["code"]] = $this->getCorrespondanceService()->getCorrespondanceByTypeAndCode("BAP", $correspondance["code"]);
                    if ($correspondanceObjects[$correspondance["code"]] === null) {
                        $info[] = "Création de la correspondance [" . $correspondance["code"] ."|" . $correspondance["intitulé"] . "]";
                        $correspondance[$correspondance["code"]] = $this->getCorrespondanceService()->createWith("BAP", $correspondance["code"], $correspondance["intitulé"], $mode === 'import');

                    }
                }

                foreach ($metiers as $metier) {
                    $metierObject = $this->getMetierService()->getMetierByReference("REFERENS3", $metier["code"]);

                    if ($metierObject === null) $metierObject = $this->getMetierService()->getMetierByLibelle($metier["intitulé"]);
                    if ($metierObject === null) {
                        $info[] = "Création du metier [" .$metier["code"] . "|" . $metier["intitulé"] . "]";
                        $metier_ = $this->getMetierService()->createWith($metier["intitulé"], "REFERENS3", $metier["code"], null, null, ($mode === 'import'));
                        foreach ($metier_familles[$metier["intitulé"]] as $famille) {
                            $info[] = "Ajout de la famille [" . $famille . "] au métier [" . $metier["intitulé"] . "]";
                            $metier_->addFamillesProfessionnelles($famillesObjects[$famille]);
                            if ($mode === 'import') $this->getMetierService()->update($metier_);
                        }
                        if (isset($metier_categories[$metier["intitulé"]])) {
                            $info[] = "Ajout de la categorie [" . $metier_categories[$metier["intitulé"]] . "] au métier [" . $metier["intitulé"] . "]";
                            $metier_->setCategorie($categoriesObjects[$metier_categories[$metier["intitulé"]]]);
                            if ($mode === 'import') $this->getMetierService()->update($metier_);
                        }
                        if (isset($metier_correspondance[$metier["intitulé"]])) {
                            $info[] = "Ajout de la correspondance [" . $metier_correspondance[$metier["intitulé"]]['intitulé'] . "] au métier [" . $metier["intitulé"] . "]";
                            $metier_->addCorrespondance($correspondanceObjects[$metier_correspondance[$metier["intitulé"]]['code']]);
                            if ($mode === 'import') $this->getMetierService()->update($metier_);
                        }
                    }
                }

            } else {
                echo "Erreur lors de l'ouverture du fichier.";
            }

        }

        return new ViewModel([
            'form' => $form,
            'info' => $info,
            'warning' => $warning,
            'error' => $error,
        ]);
    }

}