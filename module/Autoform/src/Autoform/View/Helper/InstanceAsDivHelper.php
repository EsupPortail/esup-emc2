<?php

namespace Autoform\View\Helper;

use Autoform\Entity\Db\Categorie;
use Autoform\Entity\Db\Champ;
use Autoform\Entity\Db\FormulaireInstance;
use Zend\Form\View\Helper\AbstractHelper;

class InstanceAsDivHelper extends AbstractHelper
{
    /**
     * @param FormulaireInstance $instance
     * @return string
     */
    public function render($instance) {
        $formulaire = $instance->getFormulaire();
        $reponsesTMP = $instance->getReponses();
        $reponses = [];
        foreach ($reponsesTMP as $reponse) {
            $reponses[$reponse->getChamp()->getId()] = $reponse;
        }

        $text = "";

        $categories = $formulaire->getCategories();
        $categories = array_filter($categories, function (Categorie $categorie) { return $categorie->estNonHistorise();});
        usort($categories, function (Categorie $a, Categorie $b) { return $a->getOrdre() - $b->getOrdre();});


        foreach ($categories as $categorie) {
            $champs = $categorie->getChamps();
            $champs = array_filter($champs, function (Champ $champ) { return $champ->estNonHistorise();});
            usort($champs, function (Champ $a, Champ $b) { return $a->getOrdre() - $b->getOrdre();});

            $results = [];
            foreach ($champs as $champ) {
                if (isset($reponses[$champ->getId()]) && $reponses[$champ->getId()]) $results[] = $reponses[$champ->getId()];
            }

            if (!empty($champs) && !(empty($results))) {
                $text .= '<div class="panel panel-info">';
                $text .= '<div class="panel-heading">';
                $text .= '<h3>'.$categorie->getLibelle().'</h3>';
                $text .= '</div>';
                $text .= '<div class="panel-body">';
                $text .= '<ul>';
                foreach ($champs as $champ) {
                    if (isset($reponses[$champ->getId()])) {
                        $text.= $this->getView()->champAsResult()->render($champ, $reponses[$champ->getId()]->getReponse());
                    }
                }
                $text .= '</ul>';
                $text .= '</div>';
                $text .= '</div>';
            }
        }
        return $text;
    }
}