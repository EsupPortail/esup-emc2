<?php

namespace Formation\Controller;

use Formation\Entity\Db\EnqueteReponse;
use Formation\Service\EnqueteCategorie\EnqueteCategorieServiceAwareTrait;
use Formation\Service\EnqueteReponse\EnqueteReponseServiceAwareTrait;
use Formation\Service\Formateur\FormateurServiceAwareTrait;
use Formation\Service\Formation\FormationServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class EnqueteReponseController extends AbstractActionController {
    use EnqueteCategorieServiceAwareTrait;
    use EnqueteReponseServiceAwareTrait;
    use FormationServiceAwareTrait;
    use FormateurServiceAwareTrait;

    public function afficherResultatsAction() : ViewModel
    {
        $fromQueries  = $this->params()->fromQuery();
        $formationId = (isset($fromQueries['formation']) AND isset($fromQueries['formation']['id']))?((int) $fromQueries['formation']['id']):null;
        $params = [
            'formation' => $this->getFormationService()->getFormation($formationId),
            'formateur' => $fromQueries['formateur']['id']??null,
            'formateur_denomination' => $fromQueries['formateur']['label']??null,
            'annee'     =>  $fromQueries['annee']??null,
        ];

        $categories = $this->getEnqueteCategorieService()->getEnqueteCateories();
        $reponses = $this->getEnqueteReponseService()->getEnqueteReponsesWithFiltre($params);

        $histogramme = [];
        foreach ($categories as $categorie) {
            foreach ($categorie->getQuestions() as $question) {
                if (! isset($histogramme[$question->getId()])) $histogramme[$question->getId()] = [];
                foreach (EnqueteReponse::NIVEAUX as $v => $niveau) $histogramme[$question->getId()][$v] = 0;
            }
        }
        $array = [];
        foreach ($reponses as $reponse) {
            if ($reponse->getQuestion()->estNonHistorise()) {
                $question = $reponse->getQuestion()->getId();
                $inscription = $reponse->getInscription()->getId();

                $niveau = $reponse->getNiveau();
                $description = $reponse->getDescription();

                $array[$inscription]["Niveau_" . $question] = EnqueteReponse::NIVEAUX[$niveau];
                $array[$inscription]["Commentaire_" . $question] = $description;
                $histogramme[$question][$niveau]++;
            }
        }

        return new ViewModel([
            //'session' => $this->getRequestedSession(),
            "categories" => $categories,
            "reponses" => $reponses,
            "array" => $array,
            "histogramme" => $histogramme,

            "params" => $params,
        ]);
    }
}