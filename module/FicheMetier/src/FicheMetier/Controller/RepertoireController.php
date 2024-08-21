<?php

namespace FicheMetier\Controller;

use Element\Entity\Db\CompetenceElement;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceElement\CompetenceElementServiceAwareTrait;
use Element\Service\CompetenceReferentiel\CompetenceReferentielServiceAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use FicheMetier\Form\FicheMetierImportation\FicheMetierImportationFormAwareTrait;
use FicheMetier\Service\FicheMetier\FicheMetierServiceAwareTrait;
use FicheMetier\Service\Repertoire\RepertoireServiceAwareTrait;
use FicheReferentiel\Entity\Db\FicheReferentiel;
use FicheReferentiel\Service\FicheReferentiel\FicheReferentielServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Metier\Service\Metier\MetierServiceAwareTrait;
use Metier\Service\Referentiel\ReferentielServiceAwareTrait;
use RuntimeException;

class RepertoireController extends AbstractActionController
{
    use FicheMetierImportationFormAwareTrait;
    use FicheMetierServiceAwareTrait;
    use FicheReferentielServiceAwareTrait;
    use MetierServiceAwareTrait;
    use ReferentielServiceAwareTrait;
    use RepertoireServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use CompetenceElementServiceAwareTrait;
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
            $listing = $referentielCompetenceDgafp->getCompetences();
            $maximum = 0; foreach ($listing as $item) { $maximum = max($maximum, $item->getId()); }

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
                                        $maximum++;
                                        $competences[$type_competence][$competence]['exists'] = $this->getCompetenceService()->createWith($competences[$type_competence][$competence]['libelle'], null, $type, null, $referentielCompetenceDgafp, $maximum);
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
                $metier = $this->getMetierService()->getMetierByReference('DGAFP', $code);
                if ($metier === null) {

                    $domaine = $fiche['DF'];
                    $famille = $fiche['Famille'];
//                    var_dump($code . " - " . $domaine . " " . $famille);
                    $libelle = $fiche['Intitulé'];
                    //todo ceci est suffisant il faudra un vrai truc car LibelleFéminin/LibelleMasculin Reste du libellé
                    $libelles = explode(" / ", $libelle);
//                    var_dump($libelle . " - " . ($libelles[0]??"none") . " " . ($libelles[1]??"none"));

                    $this->getMetierService()->createWith($libelle, "DGAFP", $code, $domaine, $famille);

                }
            }

            /** Gestion des fiches elle-même **************************************************************************/

            $referentielMetier = $this->getReferentielService()->getReferentielByCode('DGAFP');
            foreach ($json as $fiche) {
                $code = $fiche['Code'];
                $metier = $this->getMetierService()->getMetierByReference('DGAFP', $code);


                $definition = $fiche["Définition synthétique de l'ER"];
                $managment = $fiche["Libellé compétence managériale"];
                $activite = $fiche["Activités de l'ER"];
                $conditions = $fiche["Conditions particulières d'exercice / d'accès"];
                $tendance = $fiche["Tendance / évolution"];
                $impact = $fiche["Impact sur l'ER"];
                $code = $fiche["Code CSP"];
                $fpt = ($fiche["FPT"] !== "");
                $fph = ($fiche["FPH"] !== "");
                $fpe = ($fiche["FPE"] !== "");

                //creation de la fiche à partir du métier
                $ficheReferentiel = new FicheReferentiel();
                $ficheReferentiel->setReferentiel($referentielMetier);
                $ficheReferentiel->setMetier($metier);
                if ($mode === 'import') $this->getFicheReferentielService()->create($ficheReferentiel);

                //ajout de la description
                $ficheReferentiel->setDefinitionSynthetique(is_array($definition)?implode("\n",$definition): $definition);
                $ficheReferentiel->setCompetenceManageriale(is_array($managment)?implode("\n", $managment): $managment);
                $ficheReferentiel->setActivite(is_array($activite)?implode("\n",$activite): $activite);
                $ficheReferentiel->setConditionsParticulieres(is_array($conditions)?implode("\n",$conditions):$conditions);
                $ficheReferentiel->setTendanceEvolution(is_array($tendance)?implode("\n",$tendance):$tendance);
                $ficheReferentiel->setImpact(is_array($impact)?implode("\n",$impact):$impact);
                $ficheReferentiel->setCodeCsp($code);
                $ficheReferentiel->setFpt($fpt);
                $ficheReferentiel->setFph($fph);
                $ficheReferentiel->setFpe($fpe);
                if ($mode === 'import') $this->getFicheReferentielService()->update($ficheReferentiel);
                //ajout des competences
                $types = $this->getCompetenceTypeService()->getCompetencesTypes();
                foreach ($types as $type) {
                    $type_competence = $type->getLibelle();
                    if (!isset($competences[$type_competence])) $competences[$type_competence] = [];
                    if (isset($fiche[$type_competence])) {
                        if (is_string($fiche[$type_competence])) $fiche[$type_competence] = [$fiche[$type_competence]];
                        foreach ($fiche[$type_competence] as $competence) {
                            $competence = $this->getCompetenceService()->getCompetenceByRefentielAndLibelle($referentielCompetenceDgafp, $competence);
                            if ($competence) {
                                $element = new CompetenceElement();
                                $element->setCompetence($competence);
                                if ($mode === 'import') $this->getCompetenceElementService()->create($element);
                                $ficheReferentiel->addCompetenceElement($element);
                            }
                        }
                    }
                }
                if ($mode === 'import') $this->getFicheReferentielService()->update($ficheReferentiel);
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