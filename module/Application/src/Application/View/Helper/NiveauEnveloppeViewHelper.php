<?php

namespace Application\View\Helper;

use Application\Entity\Db\Agent;
use Application\Entity\Db\NiveauEnveloppe;
use Application\View\Renderer\PhpRenderer;
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
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('niveau-enveloppe', ['niveauEnveloppe' => $niveauEnveloppe, 'options' => $options]);
    }
}