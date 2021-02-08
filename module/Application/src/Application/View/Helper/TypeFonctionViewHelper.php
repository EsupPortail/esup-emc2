<?php

namespace Application\View\Helper;

use Metier\Entity\Db\Domaine;
use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

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