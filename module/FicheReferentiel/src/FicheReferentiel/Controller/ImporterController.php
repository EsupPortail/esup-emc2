<?php

namespace FicheReferentiel\Controller;

use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use FicheMetier\Form\FicheMetierImportation\FicheMetierImportationFormAwareTrait;
use FicheReferentiel\Service\Importer\ImporterServiceAwareTrait;
use Fichier\Service\Fichier\FichierServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class ImporterController extends AbstractActionController
{
    use CompetenceTypeServiceAwareTrait;
    use FichierServiceAwareTrait;
    use ImporterServiceAwareTrait;
    use FicheMetierImportationFormAwareTrait;

    /** L'importation du répertoire de métier de la Direction Générale de l'Administration et de la Fonction Publique */
    public function importerDgafpCsvAction(): ViewModel
    {
        $form = $this->getFicheMetierImportationForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-referentiel/importer-dgafp-csv', ['mode' => 'preview', 'path' => null], [], true));

        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $file = $request->getFiles();

            $fichier_path = $file['fichier']['tmp_name'];
            $mode = $data['mode'];
            $json = $this->getFichierService()->readCSV($fichier_path); //explose les multi ligne : est-ce une donne idée ???

            /** Récupération des compétences **************************************************************************/
            $types = $this->getCompetenceTypeService()->getCompetencesTypes();

            $listing = [];
            foreach ($types as $type) $listing[$type->getId()] = [];
            foreach ($json as $fiche) {
                foreach ($types as $type) {
                    $type_competence = $type->getLibelle();
                    if (isset($fiche[$type_competence])) {
                        if (strstr($fiche[$type_competence], PHP_EOL)) {
                            $lists = explode(PHP_EOL, $fiche[$type_competence]);
                        } else {
                            $lists = [$fiche[$type_competence]];
                        }
                        foreach ($lists as $competenceLibelle) $listing[$type->getId()][] = $competenceLibelle;
                    }
                }
            }
            $resultCompetences = $this->getImporterService()->createCompetencesDgafp($listing, ($mode === 'import'));

            /** Récupération des métiers ******************************************************************************/

            $listing = [];
            foreach ($json as $fiche) {
                $listing[] = [
                    'code' => $fiche['Code'],
                    'referentiel' => 'DGAFP',
                    'domaine' => $fiche['DF'],
                    'famille' => $fiche['Famille'],
                    'libelle' => $fiche['Intitulé'],
                ];
            }
            $resultMetiers = $this->getImporterService()->createMetierDgafp($listing, ($mode === 'import'));

            /** Génération des fiches *********************************************************************************/

            $listing = [];
            foreach ($json as $fiche) {
                $list = [
                    'code' => $fiche['Code'],
                    'definition' => $fiche["Définition synthétique de l'ER"],
                    'managment' => $fiche["Libellé compétence managériale"],
                    'activite' => $fiche["Activités de l'ER"],
                    'conditions' => $fiche["Conditions particulières d'exercice / d'accès"],
                    'tendance' => $fiche["Tendance / évolution"],
                    'impact' => $fiche["Impact sur l'ER"],
                    'codeCsp' => $fiche["Code CSP"],
                    'fpt' => ($fiche["FPT"] !== ""),
                    'fph' => ($fiche["FPH"] !== ""),
                    'fpe' => ($fiche["FPE"] !== ""),
                ];

                //ajout des competences
                $types = $this->getCompetenceTypeService()->getCompetencesTypes();
                foreach ($types as $type) {
                    $type_competence = $type->getLibelle();
                    if (!isset($competences[$type_competence])) $list[$type_competence] = [];
                    if (isset($fiche[$type_competence])) {
                        if (strstr($fiche[$type_competence], PHP_EOL)) {
                            $lists = explode(PHP_EOL, $fiche[$type_competence]);
                        } else {
                            $lists = [$fiche[$type_competence]];
                        }
                        $list[$type_competence][] = $lists;
                    }
                }
                $listing[] = $list;
            }
            $resultFiches = $this->getImporterService()->createFicheReferentielDgafp($listing, $resultCompetences['dictionnaires'], $resultMetiers['dictionnaires'], ($mode === 'import'));

            /** Sortie ************************************************************************************************/

            return new ViewModel([
                'title' => "Importation de fiches référentiels provenant de la DGAFP",
                'form' => $form,
                'mode' => $mode,
                'resultCompetences' => $resultCompetences,
                'resultMetiers' => $resultMetiers,
                'resultFiches' => $resultFiches,
            ]);

        }

        $vm = new ViewModel([
            'title' => "Importation de fiches référentiels provenant de la DGAFP",
            'form' => $form,
        ]);
        return $vm;
    }

}