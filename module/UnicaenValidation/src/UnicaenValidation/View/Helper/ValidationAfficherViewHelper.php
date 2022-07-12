<?php

namespace UnicaenValidation\View\Helper;

use UnicaenValidation\Entity\Db\ValidationInstance;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

class ValidationAfficherViewHelper extends AbstractHelper
{
    /**
     * @param ValidationInstance $validation
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($validation, $options = [])
    {
        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('validation-afficher', ['instance' => $validation]);
    }
}