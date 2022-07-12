<?php

namespace Application\View\Helper;

use Application\Entity\Db\Agent;
use Application\Entity\Db\Validation;
use Application\View\Renderer\PhpRenderer;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;


class ValidationBadgeViewHelper extends AbstractHelper
{
    /**
     * @param Validation $validation
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($validation, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('validation-badge-bis', ['validation' => $validation, 'options' => $options]);
    }
}