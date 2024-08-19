<?php

namespace Formation\View\Helper;

use Formation\Entity\Db\Inscription;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

class InscriptionViewHelper extends AbstractHelper
{
    public function __invoke(Inscription $inscription, array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('inscription', ['inscription' => $inscription, 'options' => $options]);
    }
}