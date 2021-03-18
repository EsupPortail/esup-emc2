<?php

namespace UnicaenValidation\View\Helper;

use UnicaenValidation\Entity\Db\ValidationInstance;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplatePathStack;

class ValidationBadgeViewHelper extends AbstractHelper
{
    /**
     * @param ValidationInstance|null $validation
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(?ValidationInstance $validation, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('validation-badge', ['validation' => $validation, 'options' => $options]);
    }
}