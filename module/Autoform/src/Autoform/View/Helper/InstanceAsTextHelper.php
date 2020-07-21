<?php

namespace Autoform\View\Helper;

use Autoform\Entity\Db\Categorie;
use Autoform\Entity\Db\Champ;
use Autoform\Entity\Db\FormulaireInstance;
use Zend\Form\View\Helper\AbstractHelper;

class InstanceAsTextHelper extends AbstractHelper
{
    /**
     * @param FormulaireInstance $instance
     * @param string $categorie_code
     * @return string
     */
    public function render($instance, $categorie_code = null) {
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
            if ($categorie_code === null OR $categorie_code === $categorie->getCode()) {

                $champs = $categorie->getChamps();
                $champs = array_filter($champs, function (Champ $champ) {
                    return $champ->estNonHistorise();
                });
                usort($champs, function (Champ $a, Champ $b) {
                    return $a->getOrdre() - $b->getOrdre();
                });

                $results = [];
                /** @var Champ $champ */
                foreach ($champs as $champ) {
                    if (isset($reponses[$champ->getId()]) && $reponses[$champ->getId()]) $results[] = $reponses[$champ->getId()];
                }

                if (!empty($champs) && !(empty($results))) {
                    $text .= '<h3 class="categorie">' . $categorie->getLibelle() . '</h3>';
                    $text .= '<ul>';
                    /** @var Champ $champ */
                    foreach ($champs as $champ) {
                        if (isset($reponses[$champ->getId()])) {
                            $text .= $this->getView()->champAsResult()->render($champ, $reponses[$champ->getId()]->getReponse());
                        }
                    }
                    $text .= '</ul>';
                }

            }
        }

        return $text;
    }
}