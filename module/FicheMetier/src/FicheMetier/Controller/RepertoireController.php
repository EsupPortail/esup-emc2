<?php

namespace FicheMetier\Controller;

use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceReferentiel\CompetenceReferentielServiceAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use FicheMetier\Form\FicheMetierImportation\FicheMetierImportationFormAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\Repertoire\RepertoireServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Metier\Service\Metier\MetierServiceAwareTrait;
use RuntimeException;

class RepertoireController extends AbstractActionController
{
    use FicheMetierImportationFormAwareTrait;
    use FicheMetierServiceAwareTrait;
    use MetierServiceAwareTrait;
    use RepertoireServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use CompetenceReferentielServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;

    public function lireAction(): ViewModel
    {
        $form = $this->getFicheMetierImportationForm();
        $form->setAttribute('action', $this->url()->fromRoute('fiche-metier/repertoire', ['mode' => 'preview', 'path' => null], [], true));


        $request = $this->getRequest();
        if ($request->isPost()) {
            $data = $request->getPost();
            $file = $request->getFiles();

            $fichier_path = $file['fichier']['tmp_name'];
            $mode = $data['mode'];
            $json = $this->getRepertoireService()->readCSV($fichier_path);

            /** Gestion des compétences *******************************************************************************/

            $referentielCompetenceDgafp = $this->getCompetenceReferentielService()->getCompetenceReferentielByCode('DGAFP');
            if ($referentielCompetenceDgafp === null) throw new RuntimeException("Aucun référentiel de compétence pour le direction générale de l'aldministation et de la fonction public [code:DGFAP]");

            $types = $this->getCompetenceTypeService()->getCompetencesTypes();

            $competences = [];
            foreach ($json as $fiche) {
                foreach ($types as $type) {
                    $type_competence = $type->getLibelle();
                    if (!isset($competences[$type_competence])) $competences[$type_competence] = [];
                    if (isset($fiche[$type_competence])) {
                        if (is_string($fiche[$type_competence])) $fiche[$type_competence] = [$fiche[$type_competence]];
                        foreach ($fiche[$type_competence] as $competence) {
                            $competence = trim($competence);
                            if (!isset($competences[$type_competence][$competence])) {
                                $competences[$type_competence][$competence] = [
                                    'libelle' => $competence,
                                    'exists' => $this->getCompetenceService()->getCompetenceByRefentielAndLibelle($referentielCompetenceDgafp, $competence),
                                ];
                                if ($mode === 'import') {
                                    if ($competences[$type_competence][$competence]['exists'] === null) {
                                        $competences[$type_competence][$competence]['exists'] = $this->getCompetenceService()->createWith($competences[$type_competence][$competence]['libelle'], null, $type, null, $referentielCompetenceDgafp, -1);
                                    }

                                }
                            }
                        }
                    }
                }
            }

            /** Gestion des métiers ***********************************************************************************/

            foreach ($json as $fiche) {
                $code = $fiche['Code'];
                $metier = $this->getMetierService()->getMetierByReference('DGFAP', $code);
                if ($metier === null) {

                    $domaine = $fiche['DF'];
                    $famille = $fiche['Famille'];
                    var_dump($code . " - " . $domaine . " " . $famille);
                    $libelle = $fiche['Intitulé'];
                    //todo ceci est suffisant il faudra un vrai truc car LF/LM Reste du libellé
                    $libelles = explode(" / ", $libelle);
                    var_dump($libelle . " - " . ($libelles[0]??"none") . " " . ($libelles[1]??"none"));

                    $this->getMetierService()->createWith($libelle, "DGAFP", $code, $domaine, $famille);

                }
            }
            die();
            return new ViewModel(
                [
                    'form' => $form,
                    'competences' => $competences,
                    'json' => $json,
                ]);
        }

        $vm = new ViewModel([
            'title' => "Importation d'une fiche métier",
            'form' => $form,
        ]);
        return $vm;
    }
}