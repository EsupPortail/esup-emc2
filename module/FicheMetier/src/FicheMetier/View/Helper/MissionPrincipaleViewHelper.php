<?php

namespace FicheMetier\View\Helper;

use FicheMetier\Entity\Db\Mission;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class MissionPrincipaleViewHelper extends AbstractHelper
{
    /**
     * @param Mission|null $mission
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(?Mission $mission, array $options = []): string|Partial
    {
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('mission-principale', ['mission' => $mission, 'options' => $options]);
    }
}