<?php

namespace FicheReferentiel\Controller;

use Element\Service\Competence\CompetenceServiceAwareTrait;
use Element\Service\CompetenceReferentiel\CompetenceReferentielServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Metier\Service\Metier\MetierServiceAwareTrait;

class ReferensImporterController extends AbstractActionController {
    use CompetenceReferentielServiceAwareTrait;
    use CompetenceServiceAwareTrait;
    use MetierServiceAwareTrait;

    public function indexAction(): ViewModel
    {
        $warning = "";
        $error = "";

        $referens_id = $this->params()->fromQuery('referens_id');
        $mode = $this->params()->fromQuery('mode');
        $metier = $this->getMetierService()->getMetierByReference('REFERENS', $referens_id);

        $urlWebService = "https://data.enseignementsup-recherche.gouv.fr/api/explore/v2.1/catalog/datasets/fr-esr_referentiel_metier_referens_3/records?where=referens_id%3D%27".$referens_id."%27";
        $ch = curl_init($urlWebService);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        $jsonData = curl_exec($ch);
        curl_close($ch);
        $data = json_decode($jsonData, true);

        $result = null; $libelleMission = null; $activites = null; $competences = [];
        if ($data['total_count'] === 0) {
            $error .= "<strong>Aucune donnée retournée par le webservice</strong>";
        } else {

            if ($metier === null) {
                $warning .= "<strong>Métier non renseigné dans l'application </strong>";
            }

            $result = null;
            if (isset($data['results'][0])) $result = $data['results'][0];

            $libelleMission = $result['referens_mission'];
            $activites = isset($result['referens_activites_principales'])?explode("|",$result['referens_activites_principales']):[];

            $referens = $this->getCompetenceReferentielService()->getCompetenceReferentielByCode('REFERENS');
            foreach ($result['competences_id'] as $competence_id) {
                $competence = $this->getCompetenceService()->getCompetenceByRefentiel($referens, (int) $competence_id);
                if ($competence === null) {
                    $warning .= "La compétence [".$competence_id."] n'existe pas.";
                } else {
                    $competences[] = $competence;
                }
            }
        }

// Accéder aux données du tableau PHP

        return new ViewModel([
            "warning" => $warning,
            "error" => $error,
            "result" => $result,

            "libelleMission" => $libelleMission,
            "activites" => $activites,
            "competences" => $competences,
        ]);
    }
}