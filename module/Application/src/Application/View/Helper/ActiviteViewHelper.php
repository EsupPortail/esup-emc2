<?php

namespace Application\View\Helper;

use Application\Entity\Db\Activite;
use Application\View\Renderer\PhpRenderer;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class ActiviteViewHelper extends AbstractHelper
{
    /**
     * @param Activite $activite
     * @param string $mode
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($activite, string $mode = 'affichage', array $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('activite', ['activite' => $activite, 'mode' => $mode, 'options' => $options]);
    }
}