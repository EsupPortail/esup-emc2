<?php

namespace Formation\View\Helper;

use Formation\Entity\Db\DemandeExterne;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;


class DemandeExterneViewHelper extends AbstractHelper
{
    /**
     * @param DemandeExterne $demande
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(DemandeExterne $demande, array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('demande-externe', ['demande' => $demande, 'options' => $options]);
    }
}