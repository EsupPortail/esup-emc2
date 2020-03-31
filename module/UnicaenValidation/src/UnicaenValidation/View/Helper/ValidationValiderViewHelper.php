<?php

namespace UnicaenValidation\View\Helper;

use UnicaenValidation\Service\ValidationType\ValidationTypeServiceAwareTrait;
use Zend\View\Helper\AbstractHelper;
use Zend\View\Helper\Partial;
use Zend\View\Renderer\PhpRenderer;
use Zend\View\Resolver\TemplatePathStack;

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