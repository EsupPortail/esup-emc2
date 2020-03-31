<?php

namespace UnicaenValidation\View\Helper;

use Interop\Container\ContainerInterface;
use UnicaenValidation\Service\ValidationType\ValidationTypeService;

class ValidationValiderViewHelperFactory {

    public function __invoke(ContainerInterface $container)
    {
        /** @var ValidationTypeService $validationTypeService */
        $validationTypeService = $container->get(ValidationTypeService::class);

        /** @var ValidationValiderViewHelper $helper */
        $helper = new ValidationValiderViewHelper();
        $helper->setValidationTypeService($validationTypeService);
        return $helper;
    }
}