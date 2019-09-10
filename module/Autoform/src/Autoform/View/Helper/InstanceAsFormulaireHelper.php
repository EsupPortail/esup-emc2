<?php

namespace Autoform\View\Helper;

use Autoform\Entity\Db\FormulaireInstance;
use Autoform\Entity\Db\FormulaireReponse;
use Autoform\Service\Champ\ChampServiceAwareTrait;
use Zend\Form\View\Helper\AbstractHelper;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplatePathStack;

class InstanceAsFormulaireHelper extends AbstractHelper
{
    use ChampServiceAwareTrait;

    private $debug = false;

    /**
     * @param FormulaireInstance $instance
     * @param string $url
     * @param FormulaireReponse[] $data
     * @return string
     */
    public function render($instance, $url = null, $data = null) {
        $texte = "";

        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        //$reponse = (isset($data[$champ->getId()]))?$data[$champ->getId()]->getReponse():null;
        $texte .= $view->partial('instance-as-formulaire', ['instance' => $instance, 'url' => $url, 'reponses' => $data]);
        return $texte;
    }
}