<?php

namespace EntretienProfessionnel\View\Helper;

use EntretienProfessionnel\Entity\Db\Campagne;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;


class CampagneInformationViewHelper extends AbstractHelper
{
    /**
     * @param Campagne $campagne
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(Campagne $campagne, array $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('campagne-information', ['campagne' => $campagne, 'options' => $options]);
    }
}