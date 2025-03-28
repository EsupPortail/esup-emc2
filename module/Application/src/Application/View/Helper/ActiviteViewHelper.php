<?php

namespace Application\View\Helper;

use FicheMetier\Entity\Db\Mission;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class ActiviteViewHelper extends AbstractHelper
{
    /**
     * @param Mission $mission
     * @param string $mode
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($mission, string $mode = 'affichage', array $options = [])
    {
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('activite', ['mission' => $mission, 'mode' => $mode, 'options' => $options]);
    }
}