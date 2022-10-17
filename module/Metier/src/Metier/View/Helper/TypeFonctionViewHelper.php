<?php

namespace Metier\View\Helper;

use Metier\Entity\Db\Domaine;
use Application\View\Renderer\PhpRenderer;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class TypeFonctionViewHelper extends AbstractHelper
{
    /**
     * @param Domaine $domaine
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($domaine, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('type-fonction', ['domaine' => $domaine, 'options' => $options]);
    }
}