<?php

namespace Formation\Controller;

use Formation\Entity\Db\EnqueteReponse;
use Formation\Service\EnqueteCategorie\EnqueteCategorieServiceAwareTrait;
use Formation\Service\EnqueteReponse\EnqueteReponseServiceAwareTrait;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;

class EnqueteReponseController extends AbstractActionController {
    use EnqueteCategorieServiceAwareTrait;
    use EnqueteReponseServiceAwareTrait;

    public function afficherResultatsAction() : ViewModel
    {
        $categories = $this->getEnqueteCategorieService()->getEnqueteCateories();
        $reponses = $this->getEnqueteReponseService()->getEnqueteReponses();

        $histogramme = [];
        foreach ($categories as $categorie) {
            foreach ($categorie->getQuestions() as $question) {
                $histogramme[$question->getId] = [];
                foreach (EnqueteReponse::NIVEAUX as $v => $niveau) $histogramme[$question->getId()][$v] = 0;
            }
        }
        $array = [];
        /** @var EnqueteReponse $reponse */
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
        ]);
    }
}