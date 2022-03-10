<?php

namespace Autoform\View\Helper;

use Autoform\Entity\Db\Categorie;
use Autoform\Entity\Db\Champ;
use Autoform\Entity\Db\FormulaireInstance;
use Autoform\Entity\Db\FormulaireReponse;
use Autoform\Entity\Db\Validation;use Zend\Form\View\Helper\AbstractHelper;

class ValidationAsTextHelper extends AbstractHelper
{
    /**
     * @param Validation $validation
     * @return string
     */
    public function render($validation) {
        $text = '';

        $instance = $validation->getFormulaireInstance();
        $formulaire = $instance->getFormulaire();

        $fReponses = [];
        $reponsesTMP = $instance->getReponses();
        foreach ($reponsesTMP as $reponse) {
            $fReponses[$reponse->getChamp()->getId()] = $reponse;
        }

        $vReponses = [];
        $reponsesTMP = $validation->getReponses();
        foreach ($reponsesTMP as $reponse) {
            $vReponses[$reponse->getReponse()->getChamp()->getId()] = $reponse;
        }

        $categories = $formulaire->getCategories();
        $categories = array_filter($categories, function (Categorie $categorie) { return $categorie->estNonHistorise();});
        usort($categories, function (Categorie $a, Categorie $b) { return $a->getOrdre() - $b->getOrdre();});

//        $text .= '<ul>';
        foreach ($categories as $categorie) {

            /** @var Champ[] $champs */
            $champs = $categorie->getChamps();
            $champs = array_filter($champs, function (Champ $champ) { return $champ->estNonHistorise();});
            usort($champs, function (Champ $a, Champ $b) { return $a->getOrdre() - $b->getOrdre();});

            $results = [];
            foreach ($champs as $champ) {
                $fReponse = isset($fReponses[$champ->getId()])?$fReponses[$champ->getId()]->getReponse():null;
                $vReponse = isset($vReponses[$champ->getId()])?$vReponses[$champ->getId()]->getValue():null;
                if ($fReponse && $vReponse) $results[$champ->getId()] = $fReponses[$champ->getId()];
            }

            if (!empty($champs) && !empty($results)) {
//                $text .= '<li>';
                $text .= '<h3>';
                $text .= $categorie->getLibelle();
                $text .= '</h3>';

                $text .= '<ul>';
                foreach ($champs as $champ) {
                    if (isset($results[$champ->getId()])) {
                        $text .= '<li>';
                        switch($champ->getElement()) {
                            case Champ::TYPE_MULTIPLE :
                                $text .=  $this->getView()->champAsResult()->render($champ, $vReponses[$champ->getId()]->getValue());
                                break;
                            default :
                                $text .=  $this->getView()->champAsResult()->render($champ, $fReponses[$champ->getId()]->getReponse());
                                break;
                        }
                        $text .= '</li>';
                    }
                }
                $text .= '</ul>';

//                $text .= '</li>';
            }

        }
//        $text .= '</ul>';
        return $text;
    }
}