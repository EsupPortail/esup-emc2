<?php

namespace Application\View\Helper;

use Application\Entity\Db\FicheTypeExterne;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

class FicheMetierExterneViewHelper extends AbstractHelper
{
    public function __invoke(FicheTypeExterne $fiche, array $options = ['mode' => 'affichage']): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('fiche-metier-externe', ['fiche' => $fiche, 'options' => $options]);
    }
}