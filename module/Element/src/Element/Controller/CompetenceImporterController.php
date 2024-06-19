<?php

namespace Element\Controller;

use Element\Form\CompetenceImportation\CompetenceImportationFormAwareTrait;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceReferentiel\CompetenceReferentielServiceAwareTrait;
use Element\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use RuntimeException;

class CompetenceImporterController extends AbstractActionController
{
    use CompetenceServiceAwareTrait;
    use CompetenceReferentielServiceAwareTrait;
    use CompetenceThemeServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;

    use CompetenceImportationFormAwareTrait;

    /** IMPORT ET REMPLACEMENT ****************************************************************************************/

    public function importerAction(): ViewModel
    {
        $form = $this->getCompetenceImportationForm();
        $form->setAttribute('action', $this->url()->fromRoute('competence-import', ['mode' => 'preview', 'path' => null], [], true));


        $request = $this->getRequest();
        if ($request->isPost()) {
            $error = [];
            $data = $request->getPost();
            $file = $request->getFiles();

            $fichier_path = $file['fichier']['tmp_name'];
            $mode = $data['mode'];
            if (!isset($data['referentiel']) || $data['referentiel'] === "") { throw new RuntimeException("Aucun référentiel de sélectionné.");}
            $referentiel = $this->getCompetenceReferentielService()->getCompetenceReferentiel($data['referentiel']);
            if ($referentiel === null) { throw new RuntimeException("Aucun référentiel [".$data['referentiel']."] de trouvé .");}

            $all = file_get_contents($fichier_path);
            $encoding = mb_detect_encoding($all, 'UTF-8, ISO-8859-1');

            //reading
            $array = [];
            if ($fichier_path === null or $fichier_path === '') {
                $error[] = "Aucun fichier !";
            } else {
                $handle = fopen($fichier_path, "r");

                while ($content = fgetcsv($handle, 0, ";")) {
                    $array[] = array_map(function (string $a) use ($encoding) {
//                        for ($i = 0 ; $i < strlen($a) ; $i++) {
//                            var_dump($a[$i]);
//                            var_dump(ord($a[$i]));
//                        }
                        //Note les backquote ne passe pas dans la fonction de convertion ...
                        $a = str_replace(chr(63),'\'', $a);
                        $a = mb_convert_encoding($a, 'UTF-8', $encoding);
                        return $a;
                    }, $content);
                }
            }
            $warning = [];
            if ($mode === 'import' and empty($error)) {

                $typesLibelle = [];
                $themesLibelle = [];
                foreach (array_slice($array, 1) as $line) {
                    $typesLibelle[$line[1]] = $line[1];
                    $themesLibelle[$line[0]] = $line[0];
                }
                $types = [];
                foreach ($typesLibelle as $typeLibelle) {
                    $type = $this->getCompetenceTypeService()->getCompetenceTypeByLibelle($typeLibelle);
                    if ($type === null) {
                        $type = $this->getCompetenceTypeService()->createWith($typeLibelle);
                    }
                    $types[$typeLibelle] = $type;
                }

                $themes = [];
                foreach ($themesLibelle as $themeLibelle) {
                    $theme = $this->getCompetenceThemeService()->getCompetenceThemeByLibelle($themeLibelle);
                    if ($theme === null) {
                        $theme = $this->getCompetenceThemeService()->createWith($themeLibelle);
                    }
                    $themes[$themeLibelle] = $theme;
                }

                foreach (array_slice($array, 1) as $line) {
                    $competence = $this->getCompetenceService()->getCompetenceByRefentiel($referentiel, $line[4]);
                    if ($competence === null) {
                        $this->getCompetenceService()->createWith($line[2], $line[3], $types[$line[1]], $themes[$line[0]], $referentiel, $line[4]);
                    } else {
                        $this->getCompetenceService()->updateWith($competence, $line[2], $line[3], $types[$line[1]], $themes[$line[0]]);
                    }
                }
            }

            if ($mode !== 'import') {
                $title = "Importation d'un référentiel de compétences (Prévisualisation)";
            }
            if ($mode === 'import') {
                $title = "Importation d'un référentiel de compétences (Importation)";
            }
            return new ViewModel([
                'title' => $title,
                'fichier_path' => $fichier_path,
                'form' => $form,
                'mode' => $mode,
                'error' => $error,
                'warning' => $warning,
                'array' => array_slice($array, 1),
            ]);
        }

        $vm = new ViewModel([
            'title' => "Importation d'un référentiel de compétences",
            'form' => $form,
        ]);
        return $vm;
    }
}