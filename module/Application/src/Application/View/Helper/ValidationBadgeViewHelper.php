<?php

namespace Application\View\Helper;

use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;
use UnicaenValidation\Entity\Db\ValidationInstance;


class ValidationBadgeViewHelper extends AbstractHelper
{
    public function __invoke(ValidationInstance $validation, array $options = []): string|Partial
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('validation-badge-bis', ['validation' => $validation, 'options' => $options]);
    }
}