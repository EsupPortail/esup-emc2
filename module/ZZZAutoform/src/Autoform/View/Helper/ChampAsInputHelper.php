<?php

namespace Autoform\View\Helper;

use Autoform\Entity\Db\Champ;
use Autoform\Entity\Db\FormulaireReponse;
use Autoform\Service\Champ\ChampServiceAwareTrait;
use DateTime;
use Zend\Form\View\Helper\AbstractHelper;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplatePathStack;

class ChampAsInputHelper extends AbstractHelper
{
    use ChampServiceAwareTrait;

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

//        var_dump($champ->getId());
//        var_dump(isset($data[$champ->getId()]));
//        var_dump($reponse);

        switch($champ->getElement()) {

            case Champ::TYPE_LABEL :
                if ($champ->getLibelle() !== 'empty') $texte .= '<h4>'.$champ->getLibelle().'</h4>';
                $texte .= '<p>' . $champ->getTexte() . '</p>';
                break;

            case Champ::TYPE_SPACER :
                $texte .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br/>';
                break;

            case Champ::TYPE_CHECKBOX :
                $texte .= $view->partial('input/checkbox', ['champ' => $champ, 'reponse' => $reponse]);
                break;

            case Champ::TYPE_TEXT :
                $texte .= $view->partial('input/text', ['champ' => $champ, 'reponse' => $reponse]);
                break;
            case Champ::TYPE_MULTIPLE_TEXT :
                $texte .= $view->partial('input/multiple_text', ['champ' => $champ, 'reponse' => $reponse]);
                break;

            case Champ::TYPE_NOMBRE :
                $texte .= $view->partial('input/nombre', ['champ' => $champ, 'reponse' => $reponse]);
                break;

            case Champ::TYPE_TEXTAREA :
                $texte .= $view->partial('input/textarea', ['champ' => $champ, 'reponse' => $reponse]);
                break;

            case Champ::TYPE_SELECT :
                $texte .= $view->partial('input/select', ['champ' => $champ, 'reponse' => $reponse]);
                break;

            case Champ::TYPE_PERIODE :
                $texte .= $view->partial('input/periode', ['champ' => $champ, 'reponse' => $reponse]);
                break;

            case Champ::TYPE_FORMATION :
                $texte .= $view->partial('input/formation', ['champ' => $champ, 'reponse' => $reponse]);
                break;

            case Champ::TYPE_MULTIPLE :
                $texte .= $view->partial('input/multiple', ['champ' => $champ, 'reponse' => $reponse]);
                break;

            case Champ::TYPE_ANNEE :
                $texte .= $view->partial('input/annee', ['champ' => $champ, 'reponse' => $reponse]);
                break;

            case Champ::TYPE_ENTITY :
                $options = $this->getChampService()->getAllInstance($champ->getOptions());
                $texte .= $view->partial('input/entity', ['champ' => $champ, 'options' => $options, 'reponse' => $reponse]);
                break;

            case Champ::TYPE_ENTITY_MULTI :
                $options = $this->getChampService()->getAllInstance($champ->getOptions());
                $texte .= $view->partial('input/entity-multiple', ['champ' => $champ, 'options' => $options, 'reponse' => $reponse]);
                break;
            case Champ::TYPE_CUSTOM :
                $texte .= $view->partial('input/custom', ['champ' => $champ, 'reponse' => $reponse]);
                break;
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