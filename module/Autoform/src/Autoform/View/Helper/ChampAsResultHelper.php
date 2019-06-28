<?php

namespace Autoform\View\Helper;

use Autoform\Entity\Db\Champ;
use Zend\Form\View\Helper\AbstractHelper;

class ChampAsResultHelper extends AbstractHelper
{
    /**
     * @param Champ $champ
     * @param array $data
     * @return string
     */
    public function render($champ, $data = null) {
        $texte = "";


        switch($champ->getElement()) {
            case Champ::TYPE_LABEL : return "";
                break;
            case Champ::TYPE_SPACER : return "";
                break;
            case Champ::TYPE_CHECKBOX :
                if ($data === null OR $data === 'on') $texte .= ($champ->getTexte())?:$champ->getLibelle();
                break;
            case Champ::TYPE_TEXT :
                $texte .= $champ->getLibelle(). ' : ';
                if ($data !== '') {
                    $texte .= $data;
                }
                break;
            case Champ::TYPE_TEXTAREA :
                $texte .= $champ->getLibelle(). ' : ';
                if ($data !== '') {
                    $texte .= $data;
                }
                break;
            case Champ::TYPE_SELECT :
                $texte .= $champ->getLibelle(). ' : ';
                if ($data !== 'null') {
                    $texte .= $data;
                }
                break;
            case Champ::TYPE_PERIODE :
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
        $texte = "<li>".$texte."</li>";


        return $texte;
    }
}