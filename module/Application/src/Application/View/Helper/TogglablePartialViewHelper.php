<?php

namespace Application\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;
use UnicaenParametre\Entity\Db\Parametre;

class TogglablePartialViewHelper extends AbstractHelper
{
    /**
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(string $partial, Parametre $parametre, array $variables = [], array $options = []):  string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('togglable-partial', [
            'partial' => $partial,
            'parametre' => $parametre,
            'variables' => $variables,
            'options' => $options,
        ]);
    }
}
