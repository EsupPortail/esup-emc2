<?php

namespace UnicaenEtat\View\Helper;

use Application\View\Renderer\PhpRenderer;
use UnicaenEtat\Entity\Db\EtatType;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

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