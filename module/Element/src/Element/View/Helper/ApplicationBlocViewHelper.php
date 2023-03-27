<?php

namespace Element\View\Helper;

use Element\Entity\Db\Interfaces\HasApplicationCollectionInterface;
use Laminas\View\Helper\AbstractHelper;
use Laminas\View\Helper\Partial;
use Laminas\View\Resolver\TemplatePathStack;

class ApplicationBlocViewHelper extends AbstractHelper
{
    /**
     * @param array $applications
     * @param HasApplicationCollectionInterface|null $objet
     * @param array $options
     * @return string|Partial
     */
    public function __invoke(array $applications, ?HasApplicationCollectionInterface $objet = null, array $options = [])
    {
        $view = $this->getView();
        $view->resolver()->attach(new TemplatePathStack(['script_paths' => [__DIR__ . "/partial"]]));

        return $view->partial('application-bloc', ['applications' => $applications, 'objet' => $objet, 'options' => $options]);
    }
}