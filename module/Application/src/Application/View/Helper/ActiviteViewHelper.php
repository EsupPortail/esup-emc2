<?php

namespace Application\View\Helper;

use Application\Entity\Db\Activite;
use Application\Entity\Db\FicheposteActiviteDescriptionRetiree;
use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class ActiviteViewHelper extends AbstractHelper
{
    /**
     * @param Activite $activite
     * @param string $mode
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($activite, $mode = 'affichage', $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('activite', ['activite' => $activite, 'mode' => $mode, 'options' => $options]);
    }
}