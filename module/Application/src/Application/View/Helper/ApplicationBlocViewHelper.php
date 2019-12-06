<?php

namespace Application\View\Helper;

use Application\Entity\Db\Poste;
use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;

class ApplicationBlocViewHelper extends AbstractHelper
{
    /**
     * @param array $applications
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($applications, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('application-bloc', ['applications' => $applications, 'options' => $options]);
    }
}