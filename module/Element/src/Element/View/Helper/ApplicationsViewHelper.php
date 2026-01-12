<?php

namespace Element\View\Helper;

use Element\Entity\Db\ApplicationElement;
use Element\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class ApplicationsViewHelper extends AbstractHelper
{
    /**
     * @param ApplicationElement[] $applications
     * @param HasApplicationCollectionInterface|null $objet
     * @param string $mode
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(array $applications, ?HasApplicationCollectionInterface $objet = null, string $mode = "affichage", array $options = []): string|Partial
    {
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('applications', ['applications' => $applications, 'object' => $objet, 'mode' => $mode, 'options' => $options]);
    }
}