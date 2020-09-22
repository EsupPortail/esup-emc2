<?php

namespace Autoform\View\Helper;

use Autoform\Entity\Db\Champ;
use Autoform\Service\Champ\ChampServiceAwareTrait;
use Zend\Form\View\Helper\AbstractHelper;

class ChampAsValidationHelper extends AbstractHelper
{
    use ChampServiceAwareTrait;

    /**
     * @param Champ $champ
     * @param array $data
     * @param boolean $validation
     * @return string
     */
    public function render($champ, $data = null, $validation = null) {
        $texte = "";

        switch($champ->getElement()) {
            case Champ::TYPE_LABEL : return "";
                break;
            case Champ::TYPE_SPACER : return "";
                break;
            case Champ::TYPE_MULTIPLE :
                if ($data !== 'null') {
                    $splits = explode(";", $data);
                    $validation_splits = explode(";",$validation);

                    $texte .= '<div class="col-sm-offset-1 col-sm-11">';
                    $texte .= $champ->getLibelle(). "&nbsp;:";
                    $texte .= "<ul style='list-style: none;'>";
                    foreach ($splits as $split) {
                        $replace = str_replace("_"," ",substr($split,3));
                        $found = array_search($split, $validation_splits);
                        $texte .= "<li>";
                        $texte .= '<input type="checkbox" name="reponse_'.$champ->getId().'_'.$split.'" '. (($found !== false)?'checked':'') . ' >';
                        $texte .= '<label>&nbsp;'. $replace . '</label>';
                        $texte .= "</li>";
//                        $texte .= "<li>" . $split . "</li>";
                    }
                    $texte .= "</ul>";
                    $texte .= '</div>';
                    return $texte;
                }
                break;
            case Champ::TYPE_ENTITY:
                $options = $this->getChampService()->getAllInstance($champ->getOptions());
                $reponse = "NOT FOUND !!!";
                foreach ($options as $id => $option) {
                    if ($id == $data) {
                        $reponse = $option;
                        break;
                    }
                }
                $texte .= $champ->getLibelle(). "&nbsp;:&nbsp;".$reponse;
                break;
            case Champ::TYPE_CHECKBOX :
                if ($data === null OR $data === 'on') $texte .= ($champ->getTexte())?:$champ->getLibelle();
                break;
            case Champ::TYPE_TEXT :
            case Champ::TYPE_NOMBRE :
            case Champ::TYPE_TEXTAREA :
                $texte .= $champ->getLibelle(). ' : ';
                if ($data !== '') {
                    $texte .= $data;
                }
                break;
            case Champ::TYPE_SELECT :
            case Champ::TYPE_ANNEE :
            case Champ::TYPE_PERIODE :
            case Champ::TYPE_FORMATION :
        $texte .= $champ->getLibelle(). ' : ';
                if ($data !== 'null') {
                    $texte .= $data;
                }
                break;
            default :
                $texte .= 'Type ['. $champ->getElement() .'] inconnu !';
                break;
        }

        if (! $champ->estNonHistorise()) {
            $texte = "<span class='result-historiser'>".$texte."</span>";
        }

        $result  = '';
        $result .= '<div class="col-sm-offset-1 col-sm-11">';
        $result .= '<input type="checkbox" name="reponse_'.$champ->getId().'" '. (($validation !== null)?'checked':'') . ' >';
        $result .= '<label>&nbsp;'. $texte . '</label>';
        $result .= '</div>';


        return $result;
    }
}