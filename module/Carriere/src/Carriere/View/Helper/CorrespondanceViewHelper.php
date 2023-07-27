<?php

namespace Carriere\View\Helper;

use Carriere\Entity\Db\Correspondance;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class CorrespondanceViewHelper extends AbstractHelper
{
    /**
     * @param Correspondance|null $correspondance
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(?Correspondance $correspondance, array $options = []): string|Partial
    {
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('correspondance', ['correspondance' => $correspondance, 'options' => $options]);
    }
}