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

                if ($data === null) {
                    $length = rand(8, 20);
                    for ($i = 0; $i < $length; $i++) {
                        $value = rand(0, 25) + 97;
                        $texte .= chr($value);
                        $space = rand(0, 8);
                        if ($space === 0) $texte .= ' ';
                    }
                }
                if ($data !== '') {
                    $texte .= $data;
                }
                break;
            case Champ::TYPE_TEXTAREA :
                $texte .= $champ->getLibelle(). ' : ';

                if ($data === null) {
                    $length = rand(50, 200);
                    for ($i = 0; $i < $length; $i++) {
                        $value = rand(0, 25) + 97;
                        $texte .= chr($value);
                        $space = rand(0, 8);
                        if ($space === 0) $texte .= ' ';
                    }
                }
                if ($data !== '') {
                    $texte .= $data;
                }
                break;
            case Champ::TYPE_SELECT :
                $texte .= $champ->getLibelle(). ' : ';

                if ($data === null) {
                    $options = explode(';', $champ->getOptions());
                    $position = rand(0, count($options) - 1);
                    $texte .= $options[$position];
                }
                if ($data !== 'null') {
                    $texte .= $data;
                }
                break;
            case Champ::TYPE_PERIODE :
                $texte .= $champ->getLibelle(). ' : ';

                if ($data === null) {
                    $periodes = ['Année entière', 'Premier semestre', 'Second semestre', 'Baslisée'];
                    $periode = rand(0, 3);

                    if ($periode !== 3) {
                        $texte .= $periodes[$periode];
                    } else {
                        $texte .= 'du ' . '01/01/2111' . ' au ' . '02/02/2222';
                    }
                }
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