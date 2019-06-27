<?php

namespace Application\View\Helper;

use Application\Entity\Db\FicheTypeExterne;
use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class FicheMetierExterneViewHelper extends AbstractHelper
{
    /**
     * @param FicheTypeExterne $fiche
     * @param boolean $actionnable
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($fiche, $actionnable = true, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('fiche-metier-externe', ['fiche' => $fiche, 'actionnable' => $actionnable, 'options' => $options]);
    }
}