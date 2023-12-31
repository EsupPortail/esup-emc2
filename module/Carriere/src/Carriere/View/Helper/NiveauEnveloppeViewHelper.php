<?php

namespace Carriere\View\Helper;

use Carriere\Entity\Db\NiveauEnveloppe;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class NiveauEnveloppeViewHelper extends AbstractHelper
{
    /**
     * @param NiveauEnveloppe|null $niveauEnveloppe
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(?NiveauEnveloppe $niveauEnveloppe, array $options = []): string|Partial
    {
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('niveau-enveloppe', ['niveauEnveloppe' => $niveauEnveloppe, 'options' => $options]);
    }
}