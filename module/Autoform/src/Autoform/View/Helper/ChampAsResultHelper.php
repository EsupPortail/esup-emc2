<?php

namespace Autoform\View\Helper;

use Autoform\Entity\Db\Champ;
use Autoform\Service\Champ\ChampServiceAwareTrait;
use Zend\Form\View\Helper\AbstractHelper;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplatePathStack;

class ChampAsResultHelper extends AbstractHelper
{
    use ChampServiceAwareTrait;

    /**
     * @param Champ $champ
     * @param array $data
     * @return string
     */
    public function render($champ, $data = null) {
        $texte = "";

        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        switch($champ->getElement()) {
            case Champ::TYPE_LABEL : return "";
                break;
            case Champ::TYPE_SPACER : return "";
                break;
            case Champ::TYPE_CHECKBOX :
                $texte .= $view->partial('result/checkbox', ['champ' => $champ, 'data' => $data]);
                break;
            case Champ::TYPE_TEXT :
                $texte .= $view->partial('result/text', ['champ' => $champ, 'data' => $data]);
                break;
            case Champ::TYPE_TEXTAREA :
                $texte .= $view->partial('result/textarea', ['champ' => $champ, 'data' => $data]);
                break;
            case Champ::TYPE_NOMBRE :
                $texte .= $view->partial('result/nombre', ['champ' => $champ, 'data' => $data]);
                break;
            case Champ::TYPE_SELECT :
                $texte .= $view->partial('result/select', ['champ' => $champ, 'data' => $data]);
                break;
            case Champ::TYPE_ANNEE :
                $texte .= $view->partial('result/annee', ['champ' => $champ, 'data' => $data]);
                break;
            case Champ::TYPE_PERIODE :
                $texte .= $view->partial('result/periode', ['champ' => $champ, 'data' => $data]);
                break;
            case Champ::TYPE_MULTIPLE :
                $texte .= $view->partial('result/multiple', ['champ' => $champ, 'data' => $data]);
                break;
            case Champ::TYPE_ENTITY :
                $options = $this->getChampService()->getAllInstance($champ->getOptions());
                $reponse = "NOT FOUND !!!";
                foreach ($options as $id => $option) {
                    if ($id == $data) {
                        $reponse = $option;
                        break;
                    }
                }
                $texte .= $view->partial('result/entity', ['champ' => $champ, 'data' => $reponse]);
                break;
            case Champ::TYPE_ENTITY_MULTI :
                $options = $this->getChampService()->getAllInstance($champ->getOptions());
                $ids = explode(";", (string) $data);
                $reponse = [];
                foreach ($ids as $id) {
                    $reponse[] = $options[$id];
                }
                $texte .= $view->partial('result/entity-multiple', ['champ' => $champ, 'data' => $reponse]);
                break;
            default :
                $texte .= 'Type ['. $champ->getElement() .'] inconnu !';
                break;
        }

        if (! $champ->estNonHistorise()) {
            $texte = "<span class='result-historiser'>".$texte."</span>";
        }
        if ($texte != "") $texte = "<li>".$texte."</li>";


        return $texte;
    }
}