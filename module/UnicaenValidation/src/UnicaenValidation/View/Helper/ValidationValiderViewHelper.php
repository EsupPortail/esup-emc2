<?php

namespace UnicaenValidation\View\Helper;

use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Renderer\PhpRenderer;
use Laminas\View\Resolver\TemplatePathStack;

class ValidationValiderViewHelper extends AbstractHelper
{
    use ValidationTypeServiceAwareTrait;
    /**
     * @param string $validationTypeCode
     * @param Object $entity
     * @param array $options
     * @return string|Partial
     */
    public function __invoke($validationTypeCode, $entity = null, $options = [])
    {
        $type = $this->getValidationTypeService()->getValidationTypeByCode($validationTypeCode);

        /** @var PhpRenderer $view */
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('validation-valider', ['type' => $type, 'entity' => $entity, 'options' => $options]);
    }
}