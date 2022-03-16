<?php

namespace Carriere\View\Helper;

use Carriere\Entity\Db\NiveauEnveloppe;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class NiveauEnveloppeViewHelper extends AbstractHelper
{
    /**
     * @param NiveauEnveloppe|null $niveauEnveloppe
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(?NiveauEnveloppe $niveauEnveloppe, array $options = [])
    {
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('niveau-enveloppe', ['niveauEnveloppe' => $niveauEnveloppe, 'options' => $options]);
    }
}