<?php

namespace Application\View\Helper;

use Application\Entity\Db\SpecificitePoste;
use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class SpecificitePosteViewHelper extends AbstractHelper
{
    /**
     * @param SpecificitePoste $specificite
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($specificite, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('specificite', ['specificite' => $specificite, 'options' => $options]);
    }
}