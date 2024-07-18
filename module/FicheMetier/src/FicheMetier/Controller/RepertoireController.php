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

class RepertoireController extends AbstractActionController
{
    use FicheMetierImportationFormAwareTrait;
    use FicheMetierServiceAwareTrait;
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
            $dgafp = $this->getCompetenceReferentielService()->getCompetenceReferentielByCode('DGAFP');
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
                                    'exists' => $this->getCompetenceService()->getCompetenceByRefentielAndLibelle($dgafp, $competence),
                                ];
                                if ($mode === 'import') {
                                    if ($competences[$type_competence][$competence]['exists'] === null) {
                                        $competences[$type_competence][$competence]['exists'] = $this->getCompetenceService()->createWith($competences[$type_competence][$competence]['libelle'], null, $type, null, $dgafp, -1);
                                    }

                                }
                            }
                        }
                    }
                }
            }

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