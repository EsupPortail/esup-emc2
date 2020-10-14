<?php

namespace UnicaenEtat\View\Helper;

use Application\View\Renderer\PhpRenderer;
use UnicaenEtat\Entity\Db\EtatType;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class EtatTypeViewHelper extends AbstractHelper
{
    /**
     * @param EtatType $etatType
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($etatType, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('etattype', ['etatType' => $etatType, 'options' => $options]);
    }
}