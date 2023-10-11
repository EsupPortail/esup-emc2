<?php

namespace Element\View\Helper;

use Element\Entity\Db\Competence;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class CompetenceReferenceViewHelper extends AbstractHelper
{
    public function __invoke(Competence $competence, array $options = []): string|Partial
    {
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('competence-reference', ['competence' => $competence, 'options' => $options]);
    }
}