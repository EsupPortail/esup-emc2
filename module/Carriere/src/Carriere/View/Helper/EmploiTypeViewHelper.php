<?php

namespace Carriere\View\Helper;

use Carriere\Entity\Db\EmploiType;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class EmploiTypeViewHelper extends AbstractHelper
{
    /**
     * @param EmploiType|null $emploiType
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(?EmploiType $emploiType, array $options = []): string|Partial
    {
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('emploi-type', ['emploiType' => $emploiType, 'options' => $options]);
    }
}