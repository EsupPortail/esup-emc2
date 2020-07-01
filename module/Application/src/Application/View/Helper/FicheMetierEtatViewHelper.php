<?php

namespace Application\View\Helper;

use Application\Entity\Db\FicheMetierEtat;
use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class FicheMetierEtatViewHelper extends AbstractHelper
{
    /**
     * @param FicheMetierEtat $etat
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($etat, $options = ['mode' => 'affichage'])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('fiche-metier-etat', ['etat' => $etat, 'options' => $options]);
    }
}