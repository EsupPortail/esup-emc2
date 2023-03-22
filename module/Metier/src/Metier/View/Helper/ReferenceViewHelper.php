<?php

namespace Metier\View\Helper;

use Laminas\View\Renderer\PhpRenderer;
use Metier\Entity\Db\Domaine;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;
use Metier\Entity\Db\Reference;

class ReferenceViewHelper extends AbstractHelper
{
    /**
     * @param Reference $reference
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(Reference $reference, array $options = []) : string
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('reference', ['reference' => $reference, 'options' => $options]);
    }
}