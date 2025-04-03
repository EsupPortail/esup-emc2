<?php

namespace Application\View\Helper;

use FichePoste\Entity\Db\MissionAdditionnelle;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

class SpecificiteActiviteViewHelper extends AbstractHelper
{
    public function __invoke(MissionAdditionnelle $specificiteActivite, array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('specificite-activite', ['missionAdditionnelle' => $specificiteActivite, 'options' => $options]);
    }
}