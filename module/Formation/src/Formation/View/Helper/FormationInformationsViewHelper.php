<?php

namespace Formation\View\Helper;

use Formation\Entity\Db\Formation;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;


class FormationInformationsViewHelper extends AbstractHelper
{
    /**
     * @param Formation $formation
     * @param string $mode
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(Formation $formation, string $mode = 'liste', array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('formation-informations', ['formation' => $formation, 'mode' => $mode, 'options' => $options]);
    }
}