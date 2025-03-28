<?php

namespace Application\View\Helper;

use Application\Entity\Db\FichePoste;
use Application\Entity\Db\SpecificitePoste;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

class SpecificitePosteViewHelper extends AbstractHelper
{
    public function __invoke(?FichePoste $fichePoste, ?SpecificitePoste $specificite = null, array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('specificite', ['fichePoste' => $fichePoste, 'specificite' => $specificite, 'options' => $options]);
    }
}