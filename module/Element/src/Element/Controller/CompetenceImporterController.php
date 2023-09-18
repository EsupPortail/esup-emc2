<?php

namespace Element\Controller;

use Element\Entity\Db\Competence;
use Element\Entity\Db\CompetenceTheme;
use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceTheme\CompetenceThemeServiceAwareTrait;
use Element\Service\CompetenceType\CompetenceTypeServiceAwareTrait;
use Laminas\Http\Response;
use Laminas\Mvc\Controller\AbstractActionController;

class CompetenceImporterController extends AbstractActionController {
    use CompetenceServiceAwareTrait;
    use CompetenceThemeServiceAwareTrait;
    use CompetenceTypeServiceAwareTrait;

    /** IMPORT ET REMPLACEMENT ****************************************************************************************/

    public function importerAction() : Response
    {
        $file_path = "/var/www/html/data/competence_referens3.csv";
        $content = file_get_contents($file_path);

        $types = [
            'Compétences comportementales' => $this->getCompetenceTypeService()->getCompetenceType(1),
            'Compétences opérationnelles'  => $this->getCompetenceTypeService()->getCompetenceType(2),
            'Connaissances'                => $this->getCompetenceTypeService()->getCompetenceType(3),
        ];

        $lines = explode("\n", $content);
        $nbLine = count($lines);

        for($position = 1 ; $position < $nbLine; $position++) {
            $line = $lines[$position];
            if ($line !== '') {
                $depth = 0;
                for ($i = 0; $i < strlen($line) ; $i++) {
                    if ($line[$i] === ';' and $depth === 0) $line[$i] = '|';
                    if ($line[$i] === "\"") {
                        if ($depth === 0) $depth = 1; else $depth = 0;
                    }
                }
                $elements = explode("|", $line);
                $domaine = $elements[0];
                $registre = $elements[1];
                $libelle = $elements[2];
                $definition = $elements[3];
                $id = ((int)$elements[4]);

                if ($libelle !== null and $libelle !== '') {
                    //Existe-t-elle ?
                    $theme = $this->getCompetenceThemeService()->getCompetenceThemeByLibelle($domaine);
                    if ($theme === null) {
                        $theme = new CompetenceTheme();
                        $theme->setLibelle($domaine);
                        $this->getCompetenceThemeService()->create($theme);
                    }
                    $competence = $this->getCompetenceService()->getCompetenceByIdSource("REFERENS3", $id);
                    $new_competence = ($competence === null);
                    if ($new_competence) {
                        $competence = new Competence();
                    }
                    $competence->setLibelle($libelle);
                    if ($definition !== 'Définition en attente' and $definition !== 'Définition non nécessaire') $competence->setDescription($definition); else $competence->setDescription(null);
                    $competence->setType($types[$registre]);
                    $competence->setTheme($theme);
                    $competence->setSource(Competence::SOURCE_REFERENS3);
                    $competence->setIdSource($id);
                    if ($new_competence) {
                        $this->getCompetenceService()->create($competence);
                    } else {
                        $this->getCompetenceService()->update($competence);
                    }
                }
            }
        }

        return $this->redirect()->toRoute('element/competence',[],[], true);
    }
}