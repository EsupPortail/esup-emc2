<?php

namespace Formation\View\Helper;

use Formation\Entity\Db\FormationInstance;
use Formation\Entity\Db\Inscription;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

class InscriptionsViewHelper extends AbstractHelper
{
    /**
     * @param Inscription[] $inscriptions
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(FormationInstance $session, array $inscriptions, array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('inscriptions', ['session' => $session, 'inscriptions' => $inscriptions, 'options' => $options]);
    }
}