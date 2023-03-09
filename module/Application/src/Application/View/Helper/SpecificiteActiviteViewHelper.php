<?php

namespace Application\View\Helper;

use Application\View\Renderer\PhpRenderer;
use FichePoste\Entity\Db\MissionAdditionnelle;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class SpecificiteActiviteViewHelper extends AbstractHelper
{
    /**
     * @param MissionAdditionnelle $specificiteActivite
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(MissionAdditionnelle $specificiteActivite, array $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('specificite-activite', ['missionAdditionnelle' => $specificiteActivite, 'options' => $options]);
    }
}