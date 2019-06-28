<?php

namespace Autoform\View\Helper;

use Application\View\Renderer\PhpRenderer;
use Autoform\Entity\Db\Champ;
use Autoform\Entity\Db\FormulaireReponse;
use Zend\Form\View\Helper\AbstractHelper;
use Zend\View\Resolver\TemplatePathStack;

class ChampAsInputHelper extends AbstractHelper
{
    private $debug = false;

    /**
     * @param Champ $champ
     * @param FormulaireReponse[] $data
     * @return string
     */
    public function render($champ, $data = null) {
        $texte = "";

        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        $reponse = (isset($data[$champ->getId()]))?$data[$champ->getId()]->getReponse():null;

        switch($champ->getElement()) {

            case Champ::TYPE_LABEL :
                $texte .= '<div class="row"><div class="form-group"><strong>'.$champ->getLibelle().'</strong></div></div>';
                break;

            case Champ::TYPE_SPACER :
                $texte .= '<div class="row"><div class="form-group">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div></div>';
                break;

            case Champ::TYPE_CHECKBOX :
                $texte .= $view->partial('input-checkbox', ['champ' => $champ, 'reponse' => $reponse]);

                break;

            case Champ::TYPE_TEXT :
                $texte .= $view->partial('input-text', ['champ' => $champ, 'reponse' => $reponse]);
                break;

            case Champ::TYPE_TEXTAREA :
                $texte .= $view->partial('input-textarea', ['champ' => $champ, 'reponse' => $reponse]);
                break;

            case Champ::TYPE_SELECT :
                $texte .= $view->partial('input-select', ['champ' => $champ, 'reponse' => $reponse]);
                break;

//            case Champ::TYPE_PERIODE :
//                $isBalisee = false;
//                $date1 = null;
//                $date2 = null;
//
//                $periode = ["Année entière", "Premier semestre", "Second semestre", "Balisée"];
//                $texte .= '<div class="form-group">';
//                $texte .= '<label for="textarea_'.$champ->getId().'" class="col-sm-2 control-label">';
//                $texte .= $champ->getLibelle(). '&nbsp;: ';
//                $texte .= '</label>';
//                $texte .= '<div class="col-sm-10">';
//                $texte .= '<select  name="select_'.$champ->getId().'">';
//                $texte .= '<option value="null"></option>';
//                foreach ($periode as $item) {
//                    $texte .= '<option value="'.$item.'"';
//                    if ($item === $reponse) $texte .= ' selected ';
//                    if ($item === 'Balisée' && substr($reponse, 0,3) === 'Du ' && substr($reponse, 13,4) === ' au ') {
//                        $isBalisee = true;
//                        $texte .= ' selected ';
//                    }
//                    $texte .= '>'.$item.'</option>';
//                }
//                $texte .= '</select>';
//
//                if ($isBalisee) {
//                    $splits = explode(' ', $reponse);
//                    $splits1 = explode('/', $splits[1]);
//                    $date1 = $splits1[2]. "-" . $splits1[1] . "-" .$splits1[0];
//                    $splits2 = explode('/', $splits[3]);
//                    $date2 = $splits2[2]. "-" . $splits2[1] . "-" .$splits2[0];
//
//                }
//                $texte .= '<span id="datation_'.$champ->getId().'">';
//                $texte .= ' du ';
//                $texte .= '<input  type="date" name="debut_'.$champ->getId().'"';
//                if ($isBalisee) $texte .=' value="'.$date1.'" ';
//                $texte .= '/>';
//                $texte .= ' au ';
//                $texte .= '<input  type="date" name="fin_'.$champ->getId().'"';
//                if ($isBalisee) $texte .=' value="'.$date2.'" ';
//                $texte .='/>';
//                $texte .= '</span>';
//
//                $texte .= '</div>';
//                $texte .= '</div>';
//                break;
            default :
                $texte .= 'Type ['. $champ->getElement() .'] inconnu !';
                break;
        }

        if (! $champ->estNonHistorise()) {
            $texte = "<span class='result-historiser'>".$texte."</span>";
        }

        if($this->debug) $texte = "(".$champ->getId().") ".$texte;


        return $texte;
    }
}