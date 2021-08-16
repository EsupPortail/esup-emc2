<?php

namespace Application\View\Helper;

use Application\Entity\Db\FichePoste;
use Application\Entity\Db\SpecificitePoste;
use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class SpecificitePosteViewHelper extends AbstractHelper
{
    /**
     * @param FichePoste|null $fichePoste
     * @param SpecificitePoste|null $specificite
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(?FichePoste $fichePoste, ?SpecificitePoste $specificite = null, array $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('specificite', ['fichePoste' => $fichePoste, 'specificite' => $specificite, 'options' => $options]);
    }
}