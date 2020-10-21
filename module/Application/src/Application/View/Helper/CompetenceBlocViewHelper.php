<?php

namespace Application\View\Helper;

use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class CompetenceBlocViewHelper extends AbstractHelper
{
    /**
     * @param array $competences
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(array $competences, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('competence-bloc', ['competences' => $competences, 'options' => $options]);
    }
}