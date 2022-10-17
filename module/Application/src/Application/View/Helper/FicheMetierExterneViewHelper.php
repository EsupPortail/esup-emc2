<?php

namespace Application\View\Helper;

use Application\Entity\Db\FicheTypeExterne;
use Application\View\Renderer\PhpRenderer;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class FicheMetierExterneViewHelper extends AbstractHelper
{
    /**
     * @param FicheTypeExterne $fiche
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($fiche, $options = ['mode' => 'affichage'])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('fiche-metier-externe', ['fiche' => $fiche, 'options' => $options]);
    }
}