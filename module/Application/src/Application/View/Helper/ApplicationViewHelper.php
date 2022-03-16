<?php

namespace Application\View\Helper;

use Element\Entity\Db\Application;
use Application\View\Renderer\PhpRenderer;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Resolver\TemplatePathStack;


class ApplicationViewHelper extends AbstractHelper
{
    /**
     * @param Application $application
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(Application $application, array $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('application', ['application' => $application, 'options' => $options]);
    }
}